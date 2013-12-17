<?php

namespace Test\Slinpin;

use PHPUnit_Framework_TestCase;
use Slinpin\TypeResolver;

class TypeResolverTest extends PHPUnit_Framework_TestCase
{
    public function testResolveMethod()
    {
        $expect = ['Test\Slinpin\TypeResolverTest'];

        $reflection = new \ReflectionFunction(function (TypeResolverTest $test) {
        });

        $type_resolver = new TypeResolver();

        $actual = $type_resolver->resolveMethod($reflection);

        $this->assertEquals($expect, $actual);
    }

    public function testResolveConstructorWithout()
    {
        $expect = [];

        $reflection = new \ReflectionClass('Slinpin\TypeResolver');

        $type_resolver = new TypeResolver();

        $actual = $type_resolver->resolveConstructor($reflection);

        $this->assertEquals($expect, $actual);
    }

    public function testResolveConstructorWith()
    {
        $expect = ['Slinpin\TypeResolver'];

        $reflection = new \ReflectionClass('Slinpin\ReflectionContainer');

        $type_resolver = new TypeResolver();

        $actual = $type_resolver->resolveConstructor($reflection);

        $this->assertEquals($expect, $actual);
    }
}