<?php
/*
 * Copyright (C) 2013 Terrence Howard <chemisus@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Slinpin;

use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;

/**
 * Resolves the types of a list of parameters.
 *
 * @name TypeResolver
 * @author Terrence Howard <chemisus@gmail.com>
 * @package Slinpin
 */
class TypeResolver
{
    /**
     * Returns a list of types for the parameters.
     *
     * @param \ReflectionParameter[] $parameters
     * @return string[]
     */
    public function resolve(array $parameters)
    {
        $values = [];

        foreach ($parameters as $parameter) {
            $values[] = $parameter->getClass()->getName();
        }

        return $values;
    }

    /**
     * Resolves the parameters for a constructor.
     *
     * @param $class_name
     * @return string[]
     */
    public function resolveConstructor($class_name)
    {
        $class = new ReflectionClass($class_name);

        $parameters = [];

        $constructor = $class->getConstructor();

        if ($constructor !== null) {
            $parameters = $constructor->getParameters();
        }

        return $this->resolve($parameters);
    }

    /**
     * Resolves the parameters for a callback.
     *
     * @param $callback
     * @return string[]
     */
    public function resolveFunction($callback)
    {
        $function = new ReflectionFunction($callback);

        $parameters = $function->getParameters();

        return $this->resolve($parameters);
    }

    /**
     * Resolves the parameters for a method.
     *
     * @param $class
     * @param $name
     * @return string[]
     */
    public function resolveMethod($class, $name)
    {
        $method = new ReflectionMethod($class, $name);

        $parameters = $method->getParameters();

        return $this->resolve($parameters);
    }
}