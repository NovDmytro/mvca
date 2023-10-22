<?php
namespace Engine;

class Router
{
    public $route;
    public $path;

    public function __construct($route)
    {
        $this->route = $route;
        $this->path = $this->getPathFromSeoUrl($route);
    }

    public function parsePath(): array
    {
        $url_split = explode('/', $this->path);

        $file = "src/" . $url_split[0] . '/controller/' . ucfirst($url_split[1]) . 'Controller.php';
        $class = ucfirst($url_split[0]) . "\\" . ucfirst($url_split[1]) . 'Controller';
        $method = $url_split[2];

        if (!$this->isValidPath($file, $class, $method)) {
            $file = 'src/common/controller/ErrorController.php';
            $method = 'Error404';
            $class = "Common\\ErrorController";
        }

        return [
            "file" => $file,
            "method" => $method,
            "class" => $class
        ];
    }

    private function isValidPath($file, $class, $method): bool
    {
        $is_controller_ok = true;

        if (!file_exists($file)) {
            $is_controller_ok = false;
        }
        if (method_exists($class, $method) === false) {
            $is_controller_ok = false;

        }

        return $is_controller_ok;
    }

    private function getPathFromSeoUrl($route)
    {
        $routes = include "system/config/routes.php";

        if (in_array($route, array_keys($routes))) {
            return $routes[$route];
        } else {
            return "common/Error/Error404";
        }
    }
}
