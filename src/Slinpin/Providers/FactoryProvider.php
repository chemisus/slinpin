<?php

namespace Slinpin\Providers;

use Slinpin\Provider;
use Slinpin\Container;

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