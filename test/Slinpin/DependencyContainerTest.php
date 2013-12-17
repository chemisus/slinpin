<?php

namespace Test\Slinpin;

use PHPUnit_Framework_TestCase;
use Slinpin\DependencyContainer;
use Slinpin\ReflectionContainer;
use Slinpin\TypeResolver;

class DependencyContainerTest extends PHPUnit_Framework_TestCase
{
    public function testValue()
    {
        $container = new DependencyContainer(new ReflectionContainer(new TypeResolver()));

        $container->value('a', 'A');

        $this->assertEquals('A', $container->provide('a'));
    }

    public function testCallbackNoCache()
    {
        $current = 1;

        $callback = function () use (&$current) {
            return $current++;
        };

        $container = new DependencyContainer(new ReflectionContainer(new TypeResolver()));

        $container->callback('a', $callback, false);

        $this->assertEquals(1, $container->provide('a'));
        $this->assertEquals(2, $container->provide('a'));
    }

    public function testCallback()
    {
        $current = 1;

        $callback = function () use (&$current) {
            return $current++;
        };

        $container = new DependencyContainer(new ReflectionContainer(new TypeResolver()));

        $container->callback('a', $callback);

        $this->assertEquals(1, $container->provide('a'));
        $this->assertEquals(1, $container->provide('a'));
    }
}
