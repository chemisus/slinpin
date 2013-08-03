<?php

namespace Chemisus\Slinpin;

interface DependencyResolver
{

    public function resolveDependencies(DependencyContainer $container);
}