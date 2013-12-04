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

use Mockery\CountValidator\Exception;

/**
 *
 *
 * @name DependencyContainer
 * @author Terrence Howard <chemisus@gmail.com>
 * @package Slinpin
 */
class DependencyContainer
{
    /**
     * @var DependencyProvider[]
     */
    private $providers = [];

    /**
     * @var DependencyContainer
     */
    private $parent_container;

    /**
     * @var TypeResolver
     */
    private $type_resolver;

    /**
     * @param DependencyContainer $parent_container
     * @param TypeResolver $type_resolver
     */
    public function __construct(DependencyContainer $parent_container = null, TypeResolver $type_resolver = null)
    {
        $this->parent_container = $parent_container;

        if ($type_resolver === null) {
            $type_resolver = new TypeResolver();
        }

        $this->type_resolver = $type_resolver;
    }

    /**
     * @param $key
     * @return DependencyProvider
     * @throws \Exception
     */
    public function get($key)
    {
        if (isset($this->providers[$key])) {
            return $this->providers[$key];
        }

        if ($this->parent_container !== null) {
            return $this->parent_container->get($key);
        }

        throw new DependencyNotFoundException($key);
    }

    /**
     * Provides a value for the specified key. If container is null, then it will be replaced with
     * the current container.
     *
     * @param string $key
     * @return mixed
     */
    public function provide($key)
    {
        return $this->get($key)->provide($this);
    }

    /**
     * Returns the values provided for the specified keys.
     *
     * If container is null, then it will be replaced with the current container.
     *
     * @param string[] $keys
     * @return mixed[]
     */
    public function provideAll(array $keys)
    {
        $values = [];

        foreach ($keys as $key) {
            $values[] = $this->provide($key);
        }

        return $values;
    }

    /**
     * Adds a provider for a key.
     *
     * @param string $key
     * @param DependencyProvider $value
     */
    public function set($key, DependencyProvider $value)
    {
        $this->providers[$key] = $value;
    }

    /**
     * Adds a callback provider.
     *
     * @param string $key
     * @param callable $callback
     * @param bool $cached
     */
    public function callback($key, callable $callback, $cached = true)
    {
        $value = new CallbackProvider($callback, $this->type_resolver);

        if ($cached) {
            $value = new CachedProvider($value);
        }

        $this->set($key, $value);
    }

    /**
     * Adds a value provider.
     *
     * @param string $key
     * @param mixed $value
     */
    public function value($key, $value)
    {
        $this->set($key, new ValueProvider($value));
    }

    /**
     * Creates an object of the given class.
     *
     * @param string $key
     * @param string $class_name
     */
    public function factory($key, $class_name)
    {
        $value = new FactoryProvider($class_name, $this->type_resolver);

        $this->set($key, $value);
    }
}