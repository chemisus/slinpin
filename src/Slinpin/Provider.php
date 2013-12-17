<?php

namespace Slinpin;

interface Provider
{
    public function provide(DependencyContainer $container);
}