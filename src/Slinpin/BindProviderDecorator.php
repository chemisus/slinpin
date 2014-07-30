<?php

namespace Slinpin;

class BindProviderDecorator implements Provider
{
    private $provider;
    private $interface;
    private $class;

    public function __construct(Provider $provider, $interface, $class)
    {
        $this->provider = $provider;
        $this->interface = $interface;
        $this->class = $class;
    }

    public function provide(Container $container)
    {
        return $this->provider->provide($container->withBind($this->interface, $this->class));
    }
}