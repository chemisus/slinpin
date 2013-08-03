<?php

namespace Chemisus\Slinpin;

class CallbackProvider implements Provider
{

    private $dependency_resolver;
    private $callback;

    public function __construct(DependencyResolver $dependency_resolver, $callback)
    {
        $this->dependency_resolver = $dependency_resolver;
        $this->callback = $callback;
    }

    public function provide(DependencyContainer $container)
    {
        $dependencies = $this->dependency_resolver->resolveDependencies($container);

        return call_user_func_array($this->callback, $dependencies);
    }
}