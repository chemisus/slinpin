<?php

namespace Example4;

use Container;

require_once dirname(__DIR__) . '/vendor/autoload.php';

class_alias('Slinpin\Container', 'Container');

interface Foo
{
}

class FooBar implements Foo
{
    private $id;

    public function __construct($id)
    {
        echo __METHOD__ . '(' . substr(json_encode(func_get_args()), 1, -1) . ")\n";

        $this->id = $id;
    }

    public function id()
    {
        return $this->id;
    }
}

class FooBarFactory
{
    private $id = 0;

    public function __construct()
    {
        echo __METHOD__ . '(' . substr(json_encode(func_get_args()), 1, -1) . ")\n";
    }

    public function make()
    {
        echo __METHOD__ . '(' . substr(json_encode(func_get_args()), 1, -1) . ")\n";

        $this->id++;

        return new FooBar($this->id);
    }
}

$container = new Container();

$container->factory(__NAMESPACE__ . '\Foo', __NAMESPACE__ . '\FooBarFactory', 'make', true);

$container->call(function (Foo $foo) {
    echo $foo->id() . "\n";
});

$container->call(function (Foo $foo) {
    echo $foo->id() . "\n";
});

$container->call(function (Foo $foo) {
    echo $foo->id() . "\n";
});
