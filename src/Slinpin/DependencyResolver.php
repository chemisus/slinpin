<?php

namespace Slinpin;

interface DependencyResolver
{

    public function resolveDependencies(DependencyContainer $container);
}