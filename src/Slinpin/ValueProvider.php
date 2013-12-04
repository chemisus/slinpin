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

use Closure;

/**
 * A very basic provider. When provide() is called, this just returns
 * the value that was supplied to the constructor. Nothing fancy.
 *
 * @name ValueProvider
 * @author Terrence Howard <chemisus@gmail.com>
 * @package Slinpin
 */
class ValueProvider implements DependencyProvider
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Provides a value to be injected.
     *
     * @param DependencyContainer $container
     * @return mixed
     */
    public function provide(DependencyContainer $container)
    {
        return $this->value;
    }
}