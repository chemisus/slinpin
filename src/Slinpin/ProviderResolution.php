<?php

namespace Slinpin;

class ProviderResolution implements Provider
{
    private $provider;
    private $resolver;

    public function __construct(Provider $provider, Resolver $resolver)
    {
        $this->provider = $provider;
        $this->resolver = $resolver;
    }


    public function provide(Container $container)
    {
        return $this->resolver->resolve($container, $this->provider);
    }
}