<?php

namespace Slinpin;

class ProviderBuilder implements Provider
{
    private $interface;
    private $provider;

    public function __construct(Provider $provider, $interface)
    {
        $this->provider = $provider;
        $this->interface = $interface;
    }

    /**
     * @param Decorator $decoration
     * @return ProviderDecorator
     */
    public function decorator(Decorator $decoration)
    {
        $this->provider = new ProviderDecorator($this->provider, $this->interface, $decoration);

        return $this;
    }

    public function provide(Container $container)
    {
        return $this->provider->provide($container);
    }
}