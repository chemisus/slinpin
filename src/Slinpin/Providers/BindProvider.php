<?php

namespace Slinpin\Providers;

use Slinpin\Provider;
use Slinpin\Container;

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