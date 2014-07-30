<?php

namespace Slinpin;

interface Decoration
{
    public function decorate(Container $container, $provided);
}