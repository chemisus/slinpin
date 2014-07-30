<?php

namespace Example1;

use Slinpin\Container as Container;
use Slinpin\Decorator;
use Slinpin\Resolvers\CachedResolver;

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
    private $id;

    public function __construct(Foo $foo, $id)
    {
        $this->foo = $foo;
        $this->id = $id;
    }

    public function foo()
    {
        return "Decorated({$this->id}) " . $this->foo->foo();
    }
}

class FooBarDecorator implements Decorator
{
    private $id;
    private $count = 0;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function decorate(Container $container, $provided)
    {
        echo __METHOD__ . "({$this->id})\n";

        return new FooBarDecoration($provided, $this->id . '-' . ++$this->count);
    }
}

$container = new Container();

$container->bind(__NAMESPACE__ . '\Foo', __NAMESPACE__ . '\FooBar')
    ->decorator(new FooBarDecorator(1))
    ->decorator(new FooBarDecorator(2))
    ->resolver(new CachedResolver())
    ->decorator(new FooBarDecorator(3));

echo "----------------------------------------------------\n";
echo $container->provide(__NAMESPACE__ . '\Foo')->foo() . "\n";
echo "----------------------------------------------------\n";
echo $container->provide(__NAMESPACE__ . '\Foo')->foo() . "\n";
echo "----------------------------------------------------\n";
echo $container->provide(__NAMESPACE__ . '\Foo')->foo() . "\n";
echo "----------------------------------------------------\n";
echo $container->provide(__NAMESPACE__ . '\Foo')->foo() . "\n";
echo "----------------------------------------------------\n";
