<?php
namespace Engine;

class Router
{
    public $route;
    public $path;

    private $errorPages = [];

    public function __construct($route,$errorPages)
    {
        $this->route = $route;
        $this->path = $this->getPathFromSeoUrl($route);
        $this->errorPages = $errorPages;
    }

    public function parsePath(): array
    {
        $url_split = explode('/', $this->path);

        $file = "src/" . $url_split[0] . '/controller/' . ucfirst($url_split[1]) . 'Controller.php';
        $class = ucfirst($url_split[0]) . "\\" . ucfirst($url_split[1]) . 'Controller';
        $method = $url_split[2];

        if (!$this->isValidPath($file, $class, $method)) {
            $url_split = explode('/', $this->errorPages['404']);
            $file = "src/" . $url_split[0] . '/controller/' . ucfirst($url_split[1]) . 'Controller.php';
            $class = ucfirst($url_split[0]) . "\\" . ucfirst($url_split[1]) . 'Controller';
            $method = $url_split[2];
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
            return $this->errorPages["404"];
        }
    }
}
