<?php

namespace Engine;

use Engine\Container;


class Base extends Container
{
    public function __get($dependency_name)
    {
        return $this->get($dependency_name);
    }

    public function __set($dependency_name, $dependency)
    {
        $this->set($dependency_name, $dependency);
    }
}
