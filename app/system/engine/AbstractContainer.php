<?php
namespace Engine;
use Services;
abstract class AbstractContainer
{
    protected array $objects = [];

    public function has(string $id): bool
    {
        return isset($this->objects[$id]) || class_exists($id);
    }

    /* @param string $id
     * @return mixed
     * @throws \ReflectionException
     */
    public function get(string $id): mixed
    {
        return isset($this->objects[$id]) ? $this->objects[$id]() : $this->prepareObject($id);
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