<?php

namespace Slinpin;

class Decorator implements Provider
{
    private $provider;
    private $decoration;
    private $interface;

    public function __construct(Provider $provider, $interface, Decoration $decoration)
    {
        $this->provider = $provider;
        $this->interface = $interface;
        $this->decoration = $decoration;
    }

    public function provide(Container $container)
    {
        $decorated = $this->decoration->decorate($container, $this->provider->provide($container));

        if (!$decorated instanceof $this->interface) {
            throw new \Exception('Must be an instance of ' . $this->interface);
        }

        return $decorated;
    }
}