<?php

namespace Test\Chemisus\Slinpin;

use Chemisus\Slinpin\CallbackProvider;
use Chemisus\Slinpin\DependencyResolver;
use Chemisus\Slinpin\FunctionParameterList;
use Chemisus\Slinpin\KeyInjector;
use Chemisus\Slinpin\KeysResolver;
use Chemisus\Slinpin\ParameterResolver;
use Chemisus\Slinpin\Scope;
use Chemisus\Slinpin\TypeResolver;
use Chemisus\Slinpin\ValueProvider;
use Mockery;
use PHPUnit_Framework_TestCase;
use ReflectionFunction;

class ScenarioTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    public function testValueProvider()
    {
        $scope = new Scope();

        $scope->provider(
            'a',
            new ValueProvider(
                'value'
            )
        );

        $actual = $scope->getDependency('a', $scope);

        $this->assertEquals('value', $actual);
    }

    public function testCallbackWithKeysResolverProvider()
    {
        $scope = new Scope();

        $scope->provider(
            'a',
            new ValueProvider(
                'value'
            )
        );

        $value = function ($a) {
            return $a;
        };

        $scope->provider(
            'b',
            new CallbackProvider(
                new KeysResolver(['a']),
                $value
            )
        );

        $actual = $scope->getDependency('b', $scope);

        $this->assertEquals('value', $actual);
    }

    public function testCallbackWithParameterResolverProvider()
    {
        $scope = new Scope();

        $scope->provider(
            'a',
            new ValueProvider(
                'value'
            )
        );

        $value = function ($a) {
            return $a;
        };

        $scope->provider(
            'b',
            new CallbackProvider(
                new ParameterResolver(
                    new FunctionParameterList(
                        new ReflectionFunction($value)
                    )
                ),
                $value
            )
        );

        $actual = $scope->getDependency('b', $scope);

        $this->assertEquals('value', $actual);
    }

    public function testCallbackWithTypeResolverProvider()
    {
        $parent_scope = new Scope();
        $child_scope  = new Scope($parent_scope);

        $mock = Mockery::mock('Chemisus\Slinpin\DependencyResolver');

        $value = function (DependencyResolver $resolver) {
            return $resolver;
        };

        $parent_scope->bind('Chemisus\Slinpin\DependencyResolver', 'dependency_resolver');
        $parent_scope->provider('dependency_resolver', new ValueProvider($mock));

        $parent_scope->provider(
            'b',
            new CallbackProvider(
                new TypeResolver(
                    $child_scope,
                    new FunctionParameterList(new ReflectionFunction($value))
                ),
                $value
            )
        );

        $actual = $child_scope->getDependency('b', $child_scope);

        $this->assertEquals($mock, $actual);
    }

    /**
     * @expectedException Chemisus\Slinpin\CouldNotResolveDependencyException
     */
    public function testDependencyResolveException()
    {
        $parent = new Scope();
        $child  = new Scope($parent);

        $parent->provider('a', new ValueProvider('A'));
        $child->provider('b', new ValueProvider('B'));

        $this->assertEquals('A', $child->getDependency('a', $child));
        $this->assertEquals('B', $child->getDependency('b', $child));

        $child->getDependency('c', $child);
    }

    /**
     * @expectedException Chemisus\Slinpin\CouldNotResolveBindingException
     */
    public function testBindingResolveException()
    {
        $parent = new Scope();
        $child  = new Scope($parent);

        $parent->bind('a', 'A');
        $child->bind('b', 'B');

        $this->assertEquals('A', $child->getBinding('a', $child));
        $this->assertEquals('B', $child->getBinding('b', $child));

        $child->getBinding('C', $child);
    }

    public function testMethodParameterList()
    {
    }
}
