<?php

namespace Slinpin;

class ProviderDecorator implements Provider
{
    /**
     * @var Provider
     */
    private $provider;
    private $interface;

    /**
     * @param $interface
     * @param Provider $provider
     */
    public function __construct($interface, Provider $provider)
    {
        $this->interface = $interface;
        $this->provider = $provider;
    }

    /**
     * @param Decoration $decoration
     * @return $this
     */
    public function decorate(Decoration $decoration)
    {
        $this->provider = new Decorator($this->provider, $decoration);

        return $this;
    }

    /**
     * @param Container $container
     * @return mixed
     */
    public function provide(Container $container)
    {
        return $this->provider->provide($container);
    }
}