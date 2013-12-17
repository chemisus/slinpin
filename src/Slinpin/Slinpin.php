<?php

namespace Slinpin;

class Slinpin implements InjectableDependencyContainer
{
    private $providers = [];
    private $reflection_container;

    public function __construct(ReflectionContainer $reflection_container)
    {
        $this->reflection_container = $reflection_container;
    }

    public function set($key, Provider $value)
    {
        $this->providers[$key] = $value;
    }

    public function get($key)
    {
        if (!isset($this->providers[$key])) {
            throw new DependencyDoesNotExistException();
        }

        return $this->providers[$key];
    }

    public function provide($key)
    {
        return $this->get($key)->provide($this);
    }

    public function provideAll(array $keys)
    {
        $values = [];

        foreach ($keys as $key) {
            $values[] = $this->provide($key);
        }

        return $values;
    }

    public function instance($class_name)
    {
        return $this->reflection_container->instance($class_name, $this);
    }

    public function invoke($object, $method_name)
    {
        return $this->reflection_container->invoke($object, $method_name, $this);
    }

    public function call(callable $callback)
    {
        return $this->reflection_container->call($callback, $this);
    }

    public function value($key, $value)
    {
        $this->set($key, new ValueProvider($value));
    }

    public function factory($key, $class_name, $cache = true)
    {
        $provider = new FactoryProvider($class_name, $this->reflection_container);

        if ($cache) {
            $provider = new CachedProvider($provider);
        }

        $this->set($key, $provider);
    }

    public function callback($key, callable $callback, $cache = true)
    {
        $provider = new CallbackProvider($callback, $this->reflection_container);

        if ($cache) {
            $provider = new CachedProvider($provider);
        }

        $this->set($key, $provider);
    }
}