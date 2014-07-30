<?php

namespace Slinpin;

interface Resolver
{
    public function resolve(Container $container, Provider $next);
}