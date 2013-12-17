<?php

namespace Test\Slinpin;

use PHPUnit_Framework_TestCase;
use Slinpin\ReflectionContainer;
use Slinpin\Slinpin;
use Slinpin\TypeResolver;
use Mockery;

class SlinpinTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    public function testValue()
    {
        $container = new Slinpin(new ReflectionContainer(new TypeResolver()));

        $container->value('a', 'A');

        $this->assertEquals('A', $container->provide('a'));
    }

    public function testCallbackNoCache()
    {
        $current = 1;

        $callback = function () use (&$current) {
            return $current++;
        };

        $container = new Slinpin(new ReflectionContainer(new TypeResolver()));

        $container->callback('a', $callback, false);

        $this->assertEquals(1, $container->provide('a'));
        $this->assertEquals(2, $container->provide('a'));
    }

    public function testCallback()
    {
        $current = 1;

        $callback = function (TypeResolver $resolver, TypeResolver $resolver) use (&$current) {
            return $current++;
        };

        $container = new Slinpin(new ReflectionContainer(new TypeResolver()));

        $container->value('Slinpin\TypeResolver', new TypeResolver());

        $container->callback('a', $callback);

        $this->assertEquals(1, $container->provide('a'));
        $this->assertEquals(1, $container->provide('a'));
    }

    public function testFactoryNoCache()
    {
        $container = new Slinpin(new ReflectionContainer(new TypeResolver()));

        $container->factory('a', 'Slinpin\TypeResolver', false);

        $this->assertNotSame($container->provide('a'), $container->provide('a'));
    }

    public function testFactory()
    {
        $container = new Slinpin(new ReflectionContainer(new TypeResolver()));

        $container->factory('a', 'Slinpin\TypeResolver');

        $this->assertSame($container->provide('a'), $container->provide('a'));
    }

    public function testInvoke()
    {
        $callback = function (TypeResolver $resolver) {
            return $resolver;
        };

        $resolver = new TypeResolver();

        $expect = ['Slinpin\TypeResolver'];

        $container = new Slinpin(new ReflectionContainer(new TypeResolver()));
        $container->value('ReflectionFunctionAbstract', new \ReflectionFunction($callback));

        $actual = $container->invoke($resolver, 'resolveMethod');

        $this->assertEquals($expect, $actual);
    }

    public function testInstance()
    {
        $reflection = Mockery::mock('Slinpin\ReflectionContainer');
        $reflection->shouldReceive('instance')->andReturn('A');
        $container = new Slinpin($reflection);

        $expect = 'A';
        $actual = $container->instance('a');
        $this->assertEquals($expect, $actual);
    }
}
