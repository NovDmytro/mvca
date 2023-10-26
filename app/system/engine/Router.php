<?php

namespace Engine;

class Router
{
    private mixed $path;
    private array $errorPages = [];

    public function __construct($route,$errorPages)
    {
        $this->path = $this->getPathFromRoutes(mb_strtolower($route));
        if(!$this->path){
            $this->path=$route;
        }
        $this->errorPages = $errorPages;
    }

    public function parsePath(): array
    {
        $pathDirs = explode('-', $this->path);
        $file = 'src/' . $pathDirs[0] . '/C/' . $pathDirs[1] . 'Controller.php';
        $class = $pathDirs[0] . '\\C\\' . $pathDirs[1] . 'Controller';
        $method = $pathDirs[2];

        if (!$this->isValidPath($file, $class, $method)) {
            $pathDirs = explode('-', $this->errorPages['404']);
            $file = 'src/' . $pathDirs[0] . '/C/' . $pathDirs[1] . 'Controller.php';
            $class = $pathDirs[0] . '\\C\\' . $pathDirs[1] . 'Controller';
            $method = $pathDirs[2];
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

    private function getPathFromRoutes($route)
    {
        $routes = include "system/routes.php";
        if (in_array($route, array_keys($routes))) {
            return $routes[$route];
        } else {
            return $this->errorPages["404"];
        }
    }
}
