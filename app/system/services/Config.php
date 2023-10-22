<?php

namespace Service;

class Config
{
    protected $data = [];

    public function set(string $key, $value = null)
    {
        $this->data[$key] = $value;
    }

    public function get(string $key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function getAll()
    {
        return $this->data;
    }
}
