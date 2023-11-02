<?php

namespace Engine;

class Router
{
    private mixed $path;
    private array $errorPages = [];

    public function __construct($route,$errorPages)
    {
        $routeLower='';
        if ($route) {
            $routeLower = mb_strtolower($route);
        }
        $this->path = $this->getPathFromRoutes($routeLower);
        if(!$this->path){
            $this->path=$route;
        }
        $this->errorPages = $errorPages;
    }

    public function parsePath(): array|null
    {
        $parsedPath = $this->routeFromPath($this->path);
        if (!$this->isValidPath($parsedPath['file'], $parsedPath['class'], $parsedPath['method'])) {
            $this->path = $this->errorPages['404'];
            $parsedPath = $this->routeFromPath($this->path);
        }
        return $parsedPath;
    }

    private function routeFromPath($path): array|null
    {
        $path = preg_replace('%[^A-Za-z0-9._-]%u', '', $path);
        $pathDirs = explode('-', $this->path);
        if (count($pathDirs) === 3) {
            return [
                "file" => 'src/' . $pathDirs[0] . '/C/' . $pathDirs[1] . 'Controller.php',
                "method" => $pathDirs[2],
                "class" => $pathDirs[0] . '\\C\\' . $pathDirs[1] . 'Controller',
                "route" => $path,
            ];
        }
        if (count($pathDirs) === 4 && $pathDirs[0] === "Core") {
            return [
                "file" => 'system/Core/' . $pathDirs[1] . '/C/' . $pathDirs[2] . 'Controller.php',
                "method" => $pathDirs[3],
                "class" => $pathDirs[0] . '\\' . $pathDirs[1] . '\\C\\' . $pathDirs[2] . 'Controller',
                "route" => $path,
            ];
        }
        return null;
    }

    private function isValidPath($file, $class, $method): bool
    {
        if (empty($class) || empty($method) || !file_exists($file) || !method_exists($class, $method)) {
            return false;
        }
        return true;
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
