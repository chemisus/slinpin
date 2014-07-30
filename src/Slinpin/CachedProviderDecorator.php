<?php

namespace Slinpin;

class CachedProviderDecorator implements Provider
{
    /**
     * @var Provider
     */
    private $provider;

    /**
     * @var bool
     */
    private $cached = false;

    /**
     * @var mixed
     */
    private $value;

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param Container $container
     * @return mixed
     */
    public function provide(Container $container)
    {
        if (!$this->cached) {
            $this->value = $this->provider->provide($container);
            $this->cached = true;
        }

        return $this->value;
    }
}