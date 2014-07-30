<?php

namespace Slinpin;

class BindProvider implements Provider
{
    private $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function provide(Container $container)
    {
        return $container->make($this->class);
    }
}