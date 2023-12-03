<?php

namespace Samples\C;

use Engine\Config;
use Engine\Output;
use Services\Request;
use Services\WebSocket;

class WebSocketController
{
    private Config $config;
    private Output $output;

    public function __construct(
        Config   $config,
        Output   $output,
    )
    {
        $this->config = $config;
        $this->output = $output;
    }

    public function worker(): void
    {
        // WebSocket('listenTime','lag') listenTime in seconds, lag in microseconds
        $webSocket=new WebSocket('60','100000');

        // Triggers when someone connected
        // $clientSocket is a socket resource id
        // $clientData is clients data array with headers,peer and cookies keys
        $webSocket->onConnect(function ($clientSocket, $clientData) use ($webSocket){
            //$clientSocket - socket resource id, $clientData - optional data, id is custom id, optional
            $newId=$webSocket->addClient($clientSocket,$clientData);

            //Send message to new client
            $webSocket->send($newId, json_encode(['data' => 'Hello, ' . $clientData['peer'], 'type' => 'HELO']));

            //Broadcast message to rest clients
            foreach ($webSocket->getClients() as $id=>$client) {
                if ($id != $newId) {
                $webSocket->send($id, json_encode(['data' => 'Client ' . $clientData['peer'] . ' connected', 'type' => 'HELO']));//id data
            }
            }
        });

        // Triggers when someone send message to socket
        $webSocket->onMessage(function ($clientId, $message) use ($webSocket) {
            $message = json_decode($message);
            $webSocket->send($clientId,json_encode(['data' => $message->name . '[OUT]: ' . $message->data, 'type' => 'DATA']));
            foreach ($webSocket->getClients() as $id=>$client) {
                if ($id != $clientId) {
                    $webSocket->send($id,json_encode(['data' => $message->name . '[IN]: ' . $message->data, 'type' => 'DATA']));
                }
            }
        });

        // To avoid flood better to include some action before send (onTick will do something on each tick
        $webSocket->onTick(function () use ($webSocket) {
        // Your code
        });

        // Triggers when someone exit
        $webSocket->onClose(function ($clientId) use ($webSocket) {
            foreach ($webSocket->getClients() as $id=>$client) {
                $webSocket->send($id,json_encode(['data' => 'See you '.$clientId, 'type' => 'EACH']));
            }
        });

        // Triggers before stop listening
        $webSocket->onStop(function () use ($webSocket) {
            foreach ($webSocket->getClients() as $id=>$client) {
                $webSocket->send($id,json_encode(['data' => 'Good bye everyone, socket is closing.', 'type' => 'STOP']));
            }
        });

        //Start listen loop
        $webSocket->listen(8080); //port host
    }

    public function main(): void
    {
        $view['title'] = '{{WebSocket sample}} - MVCA';
        $view['config']['language'] = $this->config->get('defaultLanguage');
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Samples/WebSocket", $view);
    }
}