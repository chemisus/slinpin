<?php

namespace Example9;

use Slinpin\Container as Container;
use Slinpin\Decoration;

require_once dirname(__DIR__) . '/vendor/autoload.php';

interface Foo
{
    public function id();
}

class FooBar implements Foo
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function id()
    {
        return $this->id;
    }
}

class FooBarDecorator implements Foo
{
    private $foo_bar;

    public function __construct(FooBar $foo_bar)
    {
        $this->foo_bar = $foo_bar;
    }

    public function id()
    {
        return $this->foo_bar->id() . $this->foo_bar->id() . $this->foo_bar->id();
    }
}

class FooBarProviderDecoration implements Decoration
{
    public function decorate(Container $container, $provided)
    {
        return $container
            ->withBind(__NAMESPACE__ . '\Foo', $provided)
            ->make(__NAMESPACE__ . '\FooBarDecorator');
    }
}

$container = new Container();

$container->callback(__NAMESPACE__ . '\Foo', new FooBar(1))
    ->cache();

$container->call(function (Foo $foo) {
    echo $foo->id() . "\n";
});

$container->call(function (Foo $foo) {
    echo $foo->id() . "\n";
});
