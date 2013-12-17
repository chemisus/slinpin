<?php

namespace Slinpin;

class ValueProvider implements Provider
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function provide(Slinpin $container)
    {
        return $this->value;
    }
}