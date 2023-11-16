<?php

namespace Services;

class WebSocket
{
    private $connectHandler;
    private $messageHandler;
    private $closeHandler;
    private $stopHandler;
    private $tickHandler;
    private array $clients;
    private string $listenTime;
    private string $lag;

    public function __construct($listenTime=60, $lag=100000)
    {
        $this->listenTime = $listenTime;
        $this->lag = $lag;
    }

    public function listen($port, $host = '0.0.0.0'): bool
    {
        $request = Request::init();
        $route = $request->GET('route', 'latin');
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind($socket, 0, $port);
        socket_listen($socket);
        $this->clients = [];
        $this->addClient($socket, '0');
        $read = [];
        $write = NULL;
        $except = NULL;
        $startTime = time();
        while (true) {
            $passedTime = time() - $startTime;
            if ($this->listenTime && $passedTime > $this->listenTime) {
                $this->onStopHandle();
                return true;
            }
            if ($this->lag) {
                usleep($this->lag);
            }
            $read = $this->getSockets();
            socket_select($read, $write, $except, 0, 10);
            foreach ($read as $id => $readSocket) {
                if ($readSocket === $socket) {
                    $newClient = socket_accept($socket);
                    $rawHeaders = socket_read($newClient, 1024);
                    $headers = [];
                    $headersLines = preg_split("/\r\n/", $rawHeaders);
                    foreach ($headersLines as $headersLine) {
                        $headersLine = chop($headersLine);
                        if (preg_match('/\A(\S+): (.*)\z/', $headersLine, $matches)) {
                            $headers[$matches[1]] = $matches[2];
                        }
                    }
                    $webSocketKey = $headers['Sec-WebSocket-Key'];
                    $webSocketAccept = base64_encode(pack('H*', sha1($webSocketKey .
                        '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
                    $sendHeaders = 'HTTP/1.1 101 Web Socket Protocol Handshake' . "\r\n" .
                        'Upgrade: websocket' . "\r\n" .
                        'Connection: Upgrade' . "\r\n" .
                        'WebSocket-Origin: ' . $host . "\r\n" .
                        'WebSocket-Location: ws://' . $host . ':' . $port . $route . "\r\n" .
                        'Sec-WebSocket-Accept:' . $webSocketAccept . "\r\n"
                        . "\r\n";
                    socket_write($newClient, $sendHeaders, strlen($sendHeaders));
                    socket_getpeername($newClient, $clientIp, $clientPort);
                    $clientData['headers'] = $headers;
                    $clientData['peer'] = $clientIp . ':' . $clientPort;
                    $clientData['cookies'] = [];
                    if (isset($headers['Cookie'])) {
                        $cookies = explode('; ', $headers['Cookie']);
                        foreach ($cookies as $cookie) {
                            $cookieParts = explode('=', $cookie, 2);
                            if (count($cookieParts) == 2) {
                                $clientData['cookies'][trim($cookieParts[0])] = urldecode(trim($cookieParts[1]));
                            }
                        }
                    }
                    $this->onConnectHandle($newClient, $clientData);
                    unset($read[$id]);
                } else {
                    while (socket_recv($readSocket, $socketData, 1024, 0) >= 1) {
                        $this->onMessageHandle($id, $this->unpack($socketData));
                        break 2;
                    }
                    $socketData = @socket_read($readSocket, 1024, PHP_NORMAL_READ);
                    if ($socketData === false) {
                        $this->onCloseHandle($id);
                        unset($this->clients[$id]);
                    }
                }
            }
            $this->onTickHandle();

        }
    }

    public function addClient($socket, $data, $id = null)
    {
        if (!$id) {
            $lastKey = array_key_last($this->clients);
            $id = $lastKey === null ? 0 : $lastKey + 1;
        }
        $this->clients[$id]['socket'] = $socket;
        $this->clients[$id]['data'] = $data;
        return $id;
    }

    public function send($id, $data): void
    {
        $sendData = $this->pack($data);
        socket_write($this->getSocket($id), $sendData, strlen($sendData));
    }

    public function getSocket($id)
    {
        return $this->clients[$id]['socket'];
    }

    public function getSockets(): array
    {
        $sockets = [];
        foreach ($this->clients as $id => $client) {
            $sockets[$id] = $client['socket'];
        }
        return $sockets;
    }

    public function getClient($id)
    {
        return $this->clients[$id];
    }

    public function getClients(): array
    {
        return $this->clients;
    }


    public function onConnect(callable $handler): void
    {
        $this->connectHandler = $handler;
    }

    public function onMessage(callable $handler): void
    {
        $this->messageHandler = $handler;
    }

    public function onTick(callable $handler): void
    {
        $this->tickHandler = $handler;
    }

    public function onStop(callable $handler): void
    {
        $this->stopHandler = $handler;
    }

    public function onClose(callable $handler): void
    {
        $this->closeHandler = $handler;
    }

    public function pack($socketData): string
    {
        $b1 = 0x80 | (0x1 & 0x0f);
        $length = strlen($socketData);

        if ($length <= 125) {

            $header = pack('CC', $b1, $length);
        } elseif ($length < 65536) {

            $header = pack('CCn', $b1, 126, $length);
        } else {
            $header = pack('CNN', $b1, 127, $length);
        }
        return $header . $socketData;
    }

    public function unpack($socketData): string
    {
        $length = ord($socketData[1]) & 127;
        if ($length == 126) {
            $masks = substr($socketData, 4, 4);
            $data = substr($socketData, 8);
        } elseif ($length == 127) {
            $masks = substr($socketData, 10, 4);
            $data = substr($socketData, 14);
        } else {
            $masks = substr($socketData, 2, 4);
            $data = substr($socketData, 6);
        }
        $socketData = '';
        for ($i = 0; $i < strlen($data); ++$i) {
            $socketData .= $data[$i] ^ $masks[$i % 4];
        }
        return $socketData;
    }

    private function onStopHandle(): void
    {
        if ($this->stopHandler) {
            call_user_func($this->stopHandler);
        }
    }

    private function onConnectHandle($client, $clientData): void
    {
        if ($this->connectHandler) {
            call_user_func($this->connectHandler, $client, $clientData);
        }
    }

    private function onMessageHandle($client, $message): void
    {
        if ($this->messageHandler) {
            call_user_func($this->messageHandler, $client, $message);
        }
    }

    private function onCloseHandle($client): void
    {
        if ($this->messageHandler) {
            call_user_func($this->closeHandler, $client);
        }
    }

    private function onTickHandle(): void
    {
        if ($this->tickHandler) {
            call_user_func($this->tickHandler);
        }
    }
}