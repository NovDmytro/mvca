<?php

namespace Service;

class Config
{
    protected array $data = [];

    public function set(string $key, $value = null): void
    {
        $this->data[$key] = $value;
    }

    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    public function getAll(): array
    {
        return $this->data;
    }
}
