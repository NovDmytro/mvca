<?php
namespace Engine;
use Services;

class Container
{
    protected array $objects = [];

    public function __construct($objects)
    {
        $this->objects = $objects;
    }

    public function has(string $id): bool
    {
        return is_callable($this->objects[$id]) || class_exists($id);
    }

    /* @param string $id
     * @return mixed
     * @throws \ReflectionException
     */
    public function get(string $id): mixed
    {
        return is_callable($this->objects[$id]) ? $this->objects[$id]() : $this->prepareObject($id);
    }

    /* @param string $class
     * @return object
     * @throws \ReflectionException
     */
    private function prepareObject(string $class): object
    {
        $classReflector = new \ReflectionClass($class);
        $constructReflector = $classReflector->getConstructor();

        if ($constructReflector === null) {
            return new $class;
        }
        $constructArguments = $constructReflector->getParameters();
        if ($constructArguments === null) {
            return new $class;
        }
        $args = [];
        foreach ($constructArguments as $argument) {
            $argumentType = $argument->getType()->getName();
            $args[$argument->getName()] = $this->get($argumentType);
        }
        return new $class(...$args);
    }
}