<?php


namespace App\classes;


use App\Exceptions\ClassNotFoundException;
use App\Interfaces\ContainerInterface;
use JetBrains\PhpStorm\Pure;

class Container implements ContainerInterface
{
    private array $items;

    public function register($class, $index)
    {
        $this->items[$index] = $class;
    }

    /**
     * @throws ClassNotFoundException
     * @throws \ReflectionException
     */
    public function get($index)
    {
        if (isset($this->items[$index])) {
            $class = $this->items[$index];
            return $this->resolveClass($index);
        }
    }

    /**
     * @throws \ReflectionException
     * @throws ClassNotFoundException
     */
    private function resolveClass($class): object
    {
        $className = $class;
        if (class_exists($className)) {
            $reflection = new \ReflectionClass($className);
            if (!$this->isConstructor($reflection) || $this->isConstructorEmpty($reflection))
                return $reflection->newInstance();
            return $this->iterateDependencies($reflection);
        } else {
            throw new ClassNotFoundException();
        }
    }

    /**
     * @throws \ReflectionException
     * @throws ClassNotFoundException
     */
    private function iterateDependencies(\ReflectionClass $reflectionClass): object
    {
        $dependecies = $reflectionClass->getConstructor()->getParameters();
        $requiredDependencies = [];
        foreach ($dependecies as $dependecy) {
            $type = $dependecy->getType();
            $requiredDependencies[] = $dependecy->getType() === null
                ? $dependecy->getDefaultValue()
                : $this->resolveClass($this->stringReflectionType($type));
        }
        return $reflectionClass->newInstanceArgs($requiredDependencies);
    }

    private function stringReflectionType(\ReflectionType $reflectionType)
    {
        return $reflectionType->getName();
    }

    #[Pure] private function isConstructor(\ReflectionClass $reflectionClass): bool
    {
        if (is_null($reflectionClass->getConstructor()))
            return false;
        return true;
    }

    #[Pure] private function isConstructorEmpty(\ReflectionClass $reflectionClass): bool
    {
        if (empty($reflectionClass->getConstructor()->getParameters()))
            return true;
        return false;
    }
}
