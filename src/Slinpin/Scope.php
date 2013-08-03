<?php

namespace Slinpin;

use Exception;

class Scope implements DependencyContainer, BindingContainer
{

    /**
     * @var Provider[]
     */
    private $dependencies = [];

    private $bindings = [];

    private $parent;

    public function __construct($parent = null)
    {
        $this->parent = $parent;
    }

    public function getDependency($key, DependencyContainer $container)
    {
        if (!isset($this->dependencies[$key])) {
            if ($this->parent === null) {
                throw new CouldNotResolveDependencyException('Can not provide ' . $key);
            }

            return $this->parent->getDependency($key, $container);
        }

        return $this->dependencies[$key]->provide($container);
    }

    public function getBinding($key, BindingContainer $container)
    {
        if (!isset($this->bindings[$key])) {
            if ($this->parent === null) {
                throw new CouldNotResolveBindingException('Can not bind ' . $key);
            }

            return $this->parent->getBinding($key, $container);
        }

        return $this->bindings[$key];
    }

    public function provider($key, Provider $provider)
    {
        $this->dependencies[$key] = $provider;
    }

    public function bind($interface, $key)
    {
        $this->bindings[$interface] = $key;
    }
}
