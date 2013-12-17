<?php

namespace Slinpin;

class CachedProvider implements Provider
{
    private $provider;
    private $value = null;
    private $cached = false;

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }

    public function provide(Slinpin $container)
    {
        if (!$this->cached) {
            $this->value  = $this->provider->provide($container);
            $this->cached = true;
        }

        return $this->value;
    }
}