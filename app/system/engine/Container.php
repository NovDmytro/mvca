<?php

namespace Engine;

class Container extends ContainerBootstrap
{
    public static array $data;

    public function get($key)
    {
        return get_class($this)::$data[$key] ?? '';
    }

    public function set($key, $value): void
    {
        Container::$data[$key] = $value;
    }

    public function has($key): bool
    {
        return isset(Container::$data[$key]);
    }
}
