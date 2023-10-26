<?php

namespace Services;

class Cookies
{
    private array $cookies;
    private int $expiresTime;

    public function __construct($expiresTime)
    {
        $this->expiresTime = $expiresTime;
    }

    public function get(string $name)
    {
        $request = Request::start();
        if (!$this->cookies[$name]) {
            $this->cookies[$name] = $request->COOKIE($name);
        }
        return $this->cookies[$name];
    }

    public function set(string $name, string $value): void
    {
        setcookie($name, $value, time() + $this->expiresTime, '/');
        $this->cookies[$name] = $value;
    }

    public function del(string $name): void
    {
        setcookie($name, '', time() - 3600, '/');
        unset($this->cookies[$name]);
    }
}
