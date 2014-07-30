<?php

namespace Slinpin;

interface Decorator
{
    public function decorate(Container $container, $provided);
}