<?php

namespace Slinpin;

class ContextualProviderDecorator implements Provider
{
    private $provider;
    private $context;

    public function __construct(Provider $provider, ProviderContext $context)
    {
        $this->provider = $provider;
        $this->context = $context;
    }

    public function provide(Container $container)
    {
        if ($this->context->applies($container)) {
            return $this->context->provide($container);
        }

        return $this->provider->provide($container);
    }
}