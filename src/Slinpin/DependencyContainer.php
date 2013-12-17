<?php

namespace Slinpin;

interface DependencyContainer
{
    public function provide($key);

    public function provideAll(array $keys);
}
