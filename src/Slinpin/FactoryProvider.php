<?php

namespace Slinpin;

class FactoryProvider implements Provider
{
    private $class;
    private $method;

    public function __construct($class, $method)
    {
        $this->class = $class;
        $this->method = $method;
    }

    public function provide(Container $container)
    {
        return $container->call(array($container->make($this->class), $this->method));
    }
}