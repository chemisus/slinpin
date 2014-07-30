<?php

namespace Slinpin\Providers;

use Slinpin\Provider;
use Slinpin\Container;

class InstanceProvider implements Provider
{
    private $instance;

    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    public function provide(Container $container)
    {
        return $this->instance;
    }
}