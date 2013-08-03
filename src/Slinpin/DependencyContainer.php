<?php

namespace Slinpin;

interface DependencyContainer
{

    public function getDependency($key, DependencyContainer $container);
}
