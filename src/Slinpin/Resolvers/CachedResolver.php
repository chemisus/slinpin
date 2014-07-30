<?php

namespace Slinpin\Resolvers;

use Slinpin\Resolver;
use Slinpin\Container;
use Slinpin\Provider;

class CachedResolver implements Resolver
{
    private $cached = false;
    private $value;

    public function resolve(Container $container, Provider $next)
    {
        if (!$this->cached) {
            $this->value = $next->provide($container);

            $this->cached = true;
        }

        return $this->value;
    }
}