<?php

namespace Chemisus\Slinpin;

use ReflectionFunction;

class FunctionParameterList implements ParameterList
{

    private $reflection_function;

    public function __construct(ReflectionFunction $reflection_function)
    {
        $this->reflection_function = $reflection_function;
    }

    public function parameters()
    {
        return $this->reflection_function->getParameters();
    }
}