<?php
namespace Engine;
use Services;
class Container extends AbstractContainer
{
    public function __construct($objects)
    {
        $this->objects = $objects;
    }
}