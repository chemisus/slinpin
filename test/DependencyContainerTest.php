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

namespace Test\Slinpin;

use PHPUnit_Framework_TestCase;
use Slinpin\DependencyContainer;
use Slinpin\DependencyNotFoundException;
use Slinpin\ValueProvider;

class DependencyContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DependencyContainer
     */
    private $container;

    public function setUp()
    {
        parent::setUp();

        $this->container = new DependencyContainer();
    }

    public function testCallback()
    {
        $this->container->callback(
            'a',
            function () {
                return 'A';
            }
        );

        $actual = $this->container->provide('a');

        $expect = 'A';

        $this->assertEquals($expect, $actual);
    }

    public function testCacheCallback()
    {
        $count = 0;

        $this->container->callback(
            'a',
            function () use (&$count) {
                return $count++;
            }
        );

        $this->container->provide('a');
        $actual = $this->container->provide('a');

        $expect = 0;

        $this->assertEquals($expect, $actual);
    }

    public function testNotCacheCallback()
    {
        $count = 0;

        $this->container->callback(
            'a',
            function () use (&$count) {
                return $count++;
            },
            false
        );

        $this->container->provide('a');
        $actual = $this->container->provide('a');

        $expect = 1;

        $this->assertEquals($expect, $actual);
    }

    public function testValue()
    {
        $this->container->value('a', 'A');

        $actual = $this->container->provide('a');

        $expect = 'A';

        $this->assertEquals($expect, $actual);
    }

    public function testProvideAll()
    {
        $this->container->value('a', 'A');
        $this->container->value('b', 'B');

        $actual = $this->container->provideAll(['a', 'b']);

        $expect = ['A', 'B'];

        $this->assertEquals($expect, $actual);
    }

    public function testCallbackWithParameters()
    {
        $expect = 'A';

        $container = $this->container;

        $this->container->value('Slinpin\ValueProvider', new ValueProvider('A'));

        $this->container->callback(
            'callback',
            function (ValueProvider $value_provider) use ($container) {
                return $value_provider->provide($container);
            }
        );

        $actual = $this->container->provide('callback');

        $this->assertEquals($expect, $actual);
    }

    public function testFactory()
    {
        $provider = new ValueProvider('A');
        $this->container->value('Slinpin\DependencyProvider', $provider);
        $this->container->factory('a', 'Slinpin\CachedProvider');
        $actual = $this->container->provide('a')->provide($this->container);
        $expect = 'A';

        $this->assertEquals($expect, $actual);
    }

    /**
     * @expectedException \Slinpin\DependencyNotFoundException
     */
    public function testDependencyNotFound()
    {
        $this->container->get('a');
    }

    public function testGet()
    {
        $this->container->value('a', 'A');

        $container = new DependencyContainer($this->container);

        $expect = 'A';

        $actual = $container->provide('a');

        $this->assertEquals($expect, $actual);
    }
}