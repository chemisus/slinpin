<?php

namespace Slinpin\Providers;

use Slinpin\Provider;
use Slinpin\Container;

class CallbackProvider implements Provider
{
    private $callback;

    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    public function provide(Container $container)
    {
        return $container->call($this->callback);
    }
}