<?php

namespace Services;

use Engine\Router;

class Controller
{
    public static function load($route): void
    {
        global $container;
        define('NESTED', true);
        $router = new Router($route, []);
        $pathData = $router->parsePath();
        $method = $pathData['method'];
        require_once($pathData['file']);
        try {
            $controller = $container->get($pathData['class']);
            $controller->$method();
        } catch (\ReflectionException $e) {
            die($e);
        }

    }
}