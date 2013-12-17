<?php

namespace Slinpin;

class ReflectionContainer
{
    /**
     * @var \ReflectionClass[]
     */
    private $reflection_classes = [];

    /**
     * @var \ReflectionFunctionAbstract[]
     */
    private $reflection_methods = [];

    /**
     * @var TypeResolver
     */
    private $type_resolver;

    public function __construct(TypeResolver $type_resolver)
    {
        $this->type_resolver = $type_resolver;
    }

    /**
     * @param $key
     * @return \ReflectionClass
     */
    public function reflectionClass($key)
    {
        if (!isset($this->reflection_classes[$key])) {
            $this->reflection_classes[$key] = new \ReflectionClass($key);
        }

        return $this->reflection_classes[$key];
    }

    /**
     * @param $class_name
     * @param $method_name
     * @return \ReflectionMethod
     */
    public function reflectionMethod($class_name, $method_name)
    {
        $key = $class_name . '::' . $method_name;

        if (!isset($this->reflection_methods[$key])) {
            $this->reflection_methods[$key] = new \ReflectionMethod($class_name, $method_name);
        }

        return $this->reflection_methods[$key];
    }

    public function instance($class_name, DependencyContainer $dependencies)
    {
        $reflection = $this->reflection($class_name);

        $keys = $this->type_resolver->resolveConstructor($reflection);

        $parameters = $dependencies->provideAll($keys);

        return $reflection->newInstanceArgs($parameters);
    }

    public function invoke($object, $method_name, DependencyContainer $dependencies)
    {
        $reflection = $this->reflectionMethod(get_class($object), $method_name);

        $keys = $this->type_resolver->resolveMethod($reflection);

        $parameters = $dependencies->provideAll($keys);

        return $reflection->newInstanceArgs($parameters);
    }
}