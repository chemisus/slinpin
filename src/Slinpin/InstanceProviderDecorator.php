<?php

namespace Slinpin;

class InstanceProviderDecorator implements Provider
{
    private $provider;
    private $interface;
    private $instance;

    public function __construct(Provider $provider, $interface, $instance)
    {
        $this->provider = $provider;
        $this->interface = $interface;
        $this->instance = $instance;
    }

    public function provide(Container $container)
    {
        return $this->provider->provide($container->withInstance($this->interface, $this->instance));
    }
}