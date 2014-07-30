<?php

namespace Example1;

use Slinpin\Container as Container;
use Slinpin\Decorator;

require_once dirname(__DIR__) . '/vendor/autoload.php';

interface Foo
{
    public function foo();
}

class FooBar implements Foo
{
    public function foo()
    {
        return 'foobar';
    }
}

class FooBarDecoration implements Foo
{
    private $foo;

    public function __construct(Foo $foo)
    {
        $this->foo = $foo;
    }

    public function foo()
    {
        return 'FOO' . $this->foo->foo();
    }
}

class FooBarDecorator implements Decorator
{
    public function decorate(Container $container, $provided)
    {
        return new FooBarDecoration($provided);
    }
}

$container = new Container();

$container->bind(__NAMESPACE__ . '\Foo', __NAMESPACE__ . '\FooBar')
    ->decorator(new FooBarDecorator());

echo $container->make(__NAMESPACE__ . '\Foo')->foo() . "\n";
