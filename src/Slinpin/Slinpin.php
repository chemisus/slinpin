<?php

namespace Slinpin;

use Needle\DependencyContainer;

class Slinpin implements DependencyContainer
{
    /**
     * @var Provider[]
     */
    private $providers = [];

    /**
     * @var ReflectionContainer
     */
    private $reflection_container;

    /**
     * @param ReflectionContainer $reflection_container
     */
    public function __construct(ReflectionContainer $reflection_container = null)
    {
        if ($reflection_container === null) {
            $reflection_container = new ReflectionContainer(
                new TypeResolver()
            );
        }

        $this->reflection_container = $reflection_container;
    }

    /**
     * Sets a provider.
     *
     * @param string $key
     * @param Provider $value
     */
    public function set($key, Provider $value)
    {
        $this->providers[$key] = $value;
    }

    /**
     * Gets a provider.
     *
     * @param string $key
     * @return Provider
     * @throws DependencyDoesNotExistException
     */
    public function get($key)
    {
        if (!isset($this->providers[$key])) {
            throw new DependencyDoesNotExistException();
        }

        return $this->providers[$key];
    }

    /**
     * Finds the provider, calls the providers provide, then returns the result.
     *
     * @param string $key
     * @return mixed
     */
    public function provide($key)
    {
        return $this->get($key)->provide($this);
    }

    /**
     * Calls provide for each key.
     *
     * @param string[] $keys
     * @return mixed[]
     */
    public function provideAll(array $keys)
    {
        $values = [];

        foreach ($keys as $key) {
            $values[] = $this->provide($key);
        }

        return $values;
    }

    /**
     * Injects dependencies into a class specified by $class_name.
     *
     * @param string $class_name
     * @return object
     */
    public function instance($class_name)
    {
        return $this->reflection_container->instance($class_name, $this);
    }

    /**
     * Invokes and injects dependencies into a method on an object, then returns the result.
     *
     * @param object $object
     * @param string $method_name
     * @return mixed
     */
    public function invoke($object, $method_name)
    {
        return $this->reflection_container->invoke($object, $method_name, $this);
    }

    /**
     * Invokes and injects dependencies into a callback, then returns the result.
     *
     * @param callable $callback
     * @return mixed
     */
    public function call(callable $callback)
    {
        return $this->reflection_container->call($callback, $this);
    }

    /**
     * Registers a value provider.
     *
     * @param string $key
     * @param mixed $value
     */
    public function value($key, $value)
    {
        $this->set($key, new ValueProvider($value));
    }

    /**
     * Registers a new factory provider.
     *
     * @param string $key
     * @param string $class_name
     * @param bool $cache
     */
    public function factory($key, $class_name, $cache = true)
    {
        $provider = new FactoryProvider($class_name, $this->reflection_container);

        if ($cache) {
            $provider = new CachedProvider($provider);
        }

        $this->set($key, $provider);
    }

    /**
     * Registers a callback provider.
     *
     * @param string $key
     * @param callable $callback
     * @param bool $cache
     */
    public function callback($key, callable $callback, $cache = true)
    {
        $provider = new CallbackProvider($callback, $this->reflection_container);

        if ($cache) {
            $provider = new CachedProvider($provider);
        }

        $this->set($key, $provider);
    }
}