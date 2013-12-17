<?php

namespace Slinpin;

class FactoryProvider implements Provider
{
    /**
     * @var string
     */
    private $class_name;

    /**
     * @var ReflectionContainer
     */
    private $reflection_container;

    /**
     * @param string $class_name
     * @param ReflectionContainer $reflection_container
     */
    public function __construct($class_name, ReflectionContainer $reflection_container)
    {
        $this->class_name = $class_name;
        $this->reflection_container = $reflection_container;
    }

    public function provide(Slinpin $container)
    {
        return $this->reflection_container->instance($this->class_name, $container);
    }
}