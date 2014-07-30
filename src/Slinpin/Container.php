<?php

namespace Slinpin;

use ReflectionClass;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;

class Container
{
    /**
     * @var Container
     */
    private $parent;

    /**
     * @var Provider[]
     */
    private $providers = array();

    /**
     * @param Container $parent
     */
    public function __construct(Container $parent = null)
    {
        $this->parent = $parent;

        $this->instance('Slinpin\Container', $this);
    }

    /**
     * @param $interface
     * @return bool
     */
    public function has($interface)
    {
        return array_key_exists($interface, $this->providers);
    }

    /**
     * @param $interface
     * @return Provider
     */
    public function get($interface)
    {
        return $this->providers[$interface];
    }

    /**
     * @return Container
     */
    public function parent()
    {
        return $this->parent;
    }

    /**
     * @param $interface
     * @param Container|null $child
     * @return mixed
     * @throws ProviderNotFoundException
     */
    public function provide($interface, Container $child = null)
    {
        $child = $child ? : $this;

        if ($this->has($interface)) {
            return $this->get($interface)->provide($child);
        }

        if ($this->parent() !== null) {
            return $this->parent()->provide($interface, $child);
        }

        if (class_exists($interface)) {
            return $child->make($interface);
        }

        throw new ProviderNotFoundException('Provider for ' . $interface . ' can not be found.');
    }

    /**
     * @param $interfaces
     * @return array
     */
    public function provideAll($interfaces)
    {
        $values = array();

        foreach ($interfaces as $interface) {
            $values[] = $this->provide($interface);
        }

        return $values;
    }

    /**
     * @param $interface
     * @param Provider $provider
     */
    public function provider($interface, Provider $provider)
    {
        $this->providers[$interface] = $provider;
    }

    /**
     * @param ReflectionFunctionAbstract $method
     * @return array
     */
    public function parameterTypes(ReflectionFunctionAbstract $method)
    {
        $types = array();

        foreach ($method->getParameters() as $parameter) {
            $class = $parameter->getClass();

            $types[] = $class->getName();
        }

        return $types;
    }

    /**
     * @param $class
     * @return object
     */
    public function make($class)
    {
        $class = new ReflectionClass($class);

        if ($constructor = $class->getConstructor()) {
            $types = $this->parameterTypes($constructor);

            $values = $this->provideAll($types);

            return $class->newInstanceArgs($values);
        }

        return $class->newInstanceWithoutConstructor();
    }

    /**
     * @param $callable
     * @return mixed
     */
    public function call($callable)
    {
        if (is_array($callable)) {
            $method = new ReflectionMethod($callable[0], $callable[1]);
        } else {
            $method = new ReflectionFunction($callable);
        }

        $types = $this->parameterTypes($method);

        $values = $this->provideAll($types);

        return call_user_func_array($callable, $values);
    }

    /**
     * @return Container
     */
    public function child()
    {
        return new Container($this);
    }

    /**
     * @param Provider $provider
     * @return ProviderDecorator
     */
    public function decorator(Provider $provider)
    {
        return $this->withInstance('Slinpin\Provider', $provider)
            ->make('Slinpin\ProviderDecorator');
    }

    /**
     * @param $interface
     * @param $instance
     * @return ProviderDecorator
     */
    public function instance($interface, $instance)
    {
        $provider = $this->decorator(new InstanceProvider($instance));

        $this->provider($interface, $provider);

        return $provider;
    }

    /**
     * @param $interface
     * @param $instance
     * @return Container
     */
    public function withInstance($interface, $instance)
    {
        $container = $this->child();

        $container->instance($interface, $instance);

        return $container;
    }

    /**
     * @param $interface
     * @param $callback
     * @return ProviderDecorator
     */
    public function callback($interface, $callback)
    {
        $provider = $this->decorator(new CallbackProvider($callback));

        $this->provider($interface, $provider);

        return $provider;
    }

    /**
     * @param $interface
     * @param $callback
     * @return Container
     */
    public function withCallback($interface, $callback)
    {
        $container = $this->child();

        $container->callback($interface, $callback);

        return $container;
    }

    /**
     * @param $interface
     * @param $class
     * @param string $method
     * @return ProviderDecorator
     */
    public function factory($interface, $class, $method = 'make')
    {
        $provider = $this->decorator(new FactoryProvider($class, $method));

        $this->provider($interface, $provider);

        return $provider;
    }

    /**
     * @param $interface
     * @param $factory
     * @param string $method
     * @return Container
     */
    public function withFactory($interface, $factory, $method = 'make')
    {
        $container = $this->child();

        $container->factory($interface, $factory, $method);

        return $container;
    }

    /**
     * @param $interface
     * @param $class
     * @return ProviderDecorator
     */
    public function bind($interface, $class)
    {
        $provider = $this->decorator(new BindProvider($class));

        $this->provider($interface, $provider);

        return $provider;
    }

    /**
     * @param $interface
     * @param $class
     * @return Container
     */
    public function withBind($interface, $class)
    {
        $container = $this->child();

        $container->bind($interface, $class);

        return $container;
    }
}