<?php

namespace Chemisus\Slinpin;

interface DependencyContainer
{

    public function getDependency($key, DependencyContainer $container);
}
