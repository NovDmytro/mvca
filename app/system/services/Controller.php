<?php

namespace Services;

use Engine\Config;
use Engine\Router;

class Controller
{
    /**
     * @throws \ReflectionException
     */
    public static function load($route): void
    {
        global $container;
        define('NESTED', true);
        $config=$container->get(Config::class);
        $router = new Router($route, $config->get('routesPath'),$config->get('sourcesPath'),$config->get('routerErrorPages'));
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