<?php

namespace Slinpin;

interface BindingContainer
{
    public function getBinding($key, BindingContainer $container);
}
