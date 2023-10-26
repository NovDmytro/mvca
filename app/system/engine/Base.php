<?php

namespace Engine;

use Engine\Container;

class Base extends Container
{
    public function __get($dependencyName)
    {
        return $this->get($dependencyName);
    }

    public function __set($dependencyName, $dependency)
    {
        $this->set($dependencyName, $dependency);
    }
}
