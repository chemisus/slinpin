<?php

namespace Slinpin;

abstract class AbstractProvider implements Provider
{
    private $provider;

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }

    public function provider()
    {
        return $this->provider;
    }
}