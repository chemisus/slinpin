<?php

namespace Slinpin;

class ProviderBuilder implements Provider
{
    private $interface;
    private $provider;

    /**
     * @param Provider $provider
     * @param $interface
     */
    public function __construct(Provider $provider, $interface)
    {
        $this->provider = $provider;
        $this->interface = $interface;
    }

    /**
     * @param Decorator $decoration
     * @return $this
     */
    public function decorator(Decorator $decoration)
    {
        $this->provider = new ProviderDecorator($this->provider, $this->interface, $decoration);

        return $this;
    }

    /**
     * @param Resolver $resolver
     * @return $this
     */
    public function resolver(Resolver $resolver)
    {
        $this->provider = new ProviderResolution($this->provider, $resolver);

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