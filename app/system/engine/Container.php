<?php
namespace Engine;

class Container
{
    public static $data = [];

    public function get($key)
    {
        return isset(get_class($this)::$data[$key]) ? get_class($this)::$data[$key] : '';
    }

    public function set($key, $value)
    {
        Container::$data[$key] = $value;
    }


    public function has($key): bool
    {
        return isset(Container::$data[$key]);
    }
}
