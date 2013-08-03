<?php

namespace Chemisus\Slinpin;

class TypeResolver implements DependencyResolver
{

    private $binding_container;

    private $parameter_list;

    public function __construct(BindingContainer $binding_container, ParameterList $parameter_list)
    {
        $this->binding_container = $binding_container;
        $this->parameter_list    = $parameter_list;
    }

    public function resolveDependencies(DependencyContainer $container)
    {
        $dependencies = [];

        foreach ($this->parameter_list->parameters() as $parameter) {
            $type = $parameter->getClass()->getName();

            $dependencies[] = $container->getDependency(
                $this->binding_container->getBinding($type, $this->binding_container),
                $container
            );
        }

        return $dependencies;
    }
}