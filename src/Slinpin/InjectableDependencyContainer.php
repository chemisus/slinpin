<?php

namespace Slinpin;

interface InjectableDependencyContainer extends DependencyContainer
{
    public function instance($class_name);

    public function invoke($object, $method_name);

    public function call(callable $callback);
}