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

/**
 * Invokes a callback and provides the result.
 *
 * @name CallbackProvider
 * @author Terrence Howard <chemisus@gmail.com>
 * @package Slinpin
 */
class CallbackProvider implements DependencyProvider
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @var TypeResolver
     */
    private $type_resolver;

    /**
     * @param callable $callback
     * @param TypeResolver $type_resolver
     */
    public function __construct(callable $callback, TypeResolver $type_resolver)
    {
        $this->callback = $callback;

        $this->type_resolver = $type_resolver;
    }

    /**
     * Provides a value to be injected.
     *
     * @param DependencyContainer $container
     * @return mixed
     */
    public function provide(DependencyContainer $container)
    {
        $keys = $this->type_resolver->resolveFunction($this->callback);

        $parameters = $container->provideAll($keys);

        return call_user_func_array($this->callback, $parameters);
    }
}