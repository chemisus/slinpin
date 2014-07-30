<?php

namespace Slinpin;

class CallbackProviderDecorator implements Provider
{
    private $provider;
    private $interface;
    private $callback;

    public function __construct(Provider $provider, $interface, $callback)
    {
        $this->provider = $provider;
        $this->interface = $interface;
        $this->callback = $callback;
    }

    public function provide(Container $container)
    {
        return $this->provider->provide($container->withCallback($this->interface, $this->callback));
    }
}