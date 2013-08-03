<?php

namespace Chemisus\Slinpin;

use ReflectionMethod;

class MethodParameterList implements ParameterList
{

    private $reflection_method;

    public function __construct(ReflectionMethod $reflection_method)
    {
        $this->reflection_method = $reflection_method;
    }

    public function parameters()
    {
        return $this->reflection_method->getParameters();
    }
}