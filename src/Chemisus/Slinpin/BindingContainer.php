<?php

namespace Chemisus\Slinpin;

interface BindingContainer
{
    public function getBinding($key, BindingContainer $container);
}
