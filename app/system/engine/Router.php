<?php

namespace Engine;

class Router
{
    private mixed $path;
    public string $routesPath;
    public string $sourcesPath;
    private array $errorCase = [];

    public function __construct($route,$routesPath,$sourcesPath,$errorCase)
    {
        $this->routesPath = $routesPath;
        $this->sourcesPath = $sourcesPath;
        $this->preparePath($route);
        $this->errorCase = $errorCase;
    }

    public function preparePath($route): void
    {
        $this->path = $this->getPathFromRoutes($route);
        if(!$this->path){
            $this->path=$route;
            define('DYNAMIC_ROUTE', true);
        }else{
            define('STATIC_ROUTE', true);
        }
    }


    public function parsePath(): array|null
    {
        $parsedPath = $this->routeFromPath($this->path);
        if (isset($this->errorCase['404']) && (!is_array($parsedPath) || !$this->isValidPath($parsedPath['file'], $parsedPath['class'], $parsedPath['method']))) {
            $this->preparePath($this->errorCase['404']);
            $parsedPath = $this->routeFromPath($this->path);
        }
        return $parsedPath;
    }


    private function routeFromPath($path): array|null
    {
        $path = preg_replace('%[^A-Za-z0-9._-]%u', '', $path);
        $pathDirs = explode('.', $this->path);
        if (count($pathDirs) === 2) {
            return [
                "file" => $this->sourcesPath . $pathDirs[0] . '/C/' . $pathDirs[0] . 'Controller.php',
                "method" => $pathDirs[1],
                "class" => $pathDirs[0] . '\\C\\' . $pathDirs[0] . 'Controller',
                "route" => $path,
                "target" => $this->sourcesPath,

            ];
        }
        if (count($pathDirs) === 3) {
            return [
                "file" => $this->sourcesPath . $pathDirs[0] . '/C/' . $pathDirs[1] . 'Controller.php',
                "method" => $pathDirs[2],
                "class" => $pathDirs[0] . '\\C\\' . $pathDirs[1] . 'Controller',
                "route" => $path,
                "target" => $this->sourcesPath,

            ];
        }
        if (count($pathDirs) === 4 && $pathDirs[0] === "Core") {
            return [
                "file" => 'system/Core/' . $pathDirs[1] . '/C/' . $pathDirs[2] . 'Controller.php',
                "method" => $pathDirs[3],
                "class" => $pathDirs[0] . '\\' . $pathDirs[1] . '\\C\\' . $pathDirs[2] . 'Controller',
                "route" => $path,
                "target" => 'core',
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
        if ($route) {
            $route = mb_strtolower($route);
        }
        $routes = include $this->routesPath;
        $routes = array_change_key_case($routes, CASE_LOWER);
        if (in_array($route, array_keys($routes))) {
            return $routes[$route];
        }
        return null;
    }
}