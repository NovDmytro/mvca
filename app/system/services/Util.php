<?php

namespace Services;

class Util
{
    public function fetch($scheme, $domain, $request, $settings): bool|string
    {
        $port = ($scheme === 'https') ? 443 : 80;
        $timeout = 2;
        if ($scheme === 'https') {
            $context = stream_context_create([
                'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
                'verify_depth' => 0
                ]
            ]);
            $socket = stream_socket_client(
                'tcp://' . $domain . ':' . $port,
                $errNo,
                $errStr,
                $timeout,
                STREAM_CLIENT_CONNECT,
                $context);
            if (!$socket) {
                return false;
            }
            $cryptoEnabled = stream_socket_enable_crypto($socket, true,
                STREAM_CRYPTO_METHOD_SSLv2_CLIENT |
                STREAM_CRYPTO_METHOD_SSLv3_CLIENT |
                STREAM_CRYPTO_METHOD_TLSv1_0_CLIENT |
                STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT |
                STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT);
            if ($cryptoEnabled !== true) {
                fclose($socket);
                return false;
            }
        } else {
            $socket = stream_socket_client(
                'tcp://' . $domain . ':' . $port,
                $errNo,
                $errStr,
                $timeout);
            if (!$socket) {
                return false;
            }
        }

        if ($settings['async']) {
            if ($socket) {
                fwrite($socket, $request);
                fclose($socket);
                return true;
            } else {
                return false;
            }
        } else {
            if ($socket) {
                fwrite($socket, $request);
                $response = '';
                while (!feof($socket)) {
                    $response .= fgets($socket);
                }
                fclose($socket);
                list(, $body) = explode("\r\n\r\n", $response, 2);
                return $body;
            } else {
                return false;
            }
        }
    }

}
