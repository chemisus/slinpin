<?php

namespace Slinpin;

use ReflectionFunction;

class ParameterResolver implements DependencyResolver
{

    private $parameter_list;

    public function __construct(ParameterList $parameter_list)
    {
        $this->parameter_list = $parameter_list;
    }

    public function resolveDependencies(DependencyContainer $container)
    {
        $dependencies = [];

        foreach ($this->parameter_list->parameters() as $parameter) {
            $dependencies[] = $container->getDependency($parameter->getName(), $container);
        }

        return $dependencies;
    }
}