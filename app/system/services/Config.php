<?php

namespace Services;

class Config
{
    private array $data = [];

    public function __construct($data = [])
    {
        $this->data=$data;

    }

    public function setArray(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    public function set(string $key, $value = null): void
    {
        $this->data[$key] = $value;
    }

    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    public function getArray(): array
    {
        return $this->data;
    }
}
