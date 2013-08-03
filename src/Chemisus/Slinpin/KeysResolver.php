<?php

namespace Chemisus\Slinpin;

class KeysResolver implements DependencyResolver
{

    private $keys;

    public function __construct($keys)
    {
        $this->keys = $keys;
    }

    public function resolveDependencies(DependencyContainer $container)
    {
        $dependencies = [];

        foreach ($this->keys as $key) {
            $dependencies[] = $container->getDependency($key, $container);
        }

        return $dependencies;
    }
}