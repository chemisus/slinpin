<?php

namespace Slinpin;

class CallbackProvider implements Provider
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @var ReflectionContainer
     */
    private $reflection_container;

    /**
     * @param callable $callback
     * @param ReflectionContainer $reflection_container
     */
    public function __construct(callable $callback, ReflectionContainer $reflection_container)
    {
        $this->callback = $callback;
        $this->reflection_container = $reflection_container;
    }

    public function provide(DependencyContainer $container)
    {
        return $this->reflection_container->call($this->callback, $container);
    }
}