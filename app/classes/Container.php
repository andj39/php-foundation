<?php


namespace App\classes;


use App\Exceptions\ClassNotFoundException;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface
{
    private array $classContainer;

    public function register($class, $id)
    {
        $this->classContainer[$id] = $class;
    }

    /**
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws \ReflectionException
     */
    public function get($id): object
    {
        if (isset($this->classContainer[$id])) {
            return $this->resolveClass($id);
        }
        throw new ClassNotFoundException('Class could not be found in container');
    }

    /**
     * @throws \ReflectionException
     * @throws NotFoundExceptionInterface
     */
    private function resolveClass($class): object
    {
        $className = $class;
        if ($this->isClassInContainer($className)) {
            if (is_object($this->classContainer[$className]))
                return $this->classContainer[$className];
            if (class_exists($className))
                return $this->resolveReflectionClass($className);
            if (interface_exists($className))
                return $this->resolveReflectionClass($this->classContainer[$className]);
        }
        if (class_exists($className)) {
            return $this->resolveReflectionClass($className);
        } else {
            throw new ClassNotFoundException($className);
        }
    }

    /**
     * @throws \ReflectionException
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

    private function isConstructor(\ReflectionClass $reflectionClass): bool
    {
        if (is_null($reflectionClass->getConstructor()))
            return false;
        return true;
    }

    private function isConstructorEmpty(\ReflectionClass $reflectionClass): bool
    {
        if (empty($reflectionClass->getConstructor()->getParameters()))
            return true;
        return false;
    }

    private function isClassInContainer($class) : bool
    {
        if (isset($this->classContainer[$class])) {
            return true;
        }
        return false;
    }

    /**
     * @param string $className
     * @return object
     * @throws ClassNotFoundException
     * @throws \ReflectionException
     */
    private function resolveReflectionClass(string $className): object
    {
        $reflectionClass = new \ReflectionClass($className);
        if (!$this->isConstructor($reflectionClass) || $this->isConstructorEmpty($reflectionClass))
            return $reflectionClass->newInstance();
        return $this->iterateDependencies($reflectionClass);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        if ($this->classContainer[$id]) {
            return true;
        }
        return false;
    }
}
