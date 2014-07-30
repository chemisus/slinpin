<?php

namespace Slinpin;

class FactoryProviderDecorator implements Provider
{
    private $provider;
    private $interface;
    private $class;
    private $method;

    public function __construct(Provider $provider, $interface, $class, $method = 'make')
    {
        $this->provider = $provider;
        $this->interface = $interface;
        $this->class = $class;
        $this->method = $method;
    }

    public function provide(Container $container)
    {
        return $this->provider->provide($container->withFactory($this->interface, $this->class, $this->method));
    }
}