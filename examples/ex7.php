<?php

namespace Example7;

use Container;

require_once dirname(__DIR__) . '/vendor/autoload.php';

class_alias('Slinpin\Container', 'Container');

interface Foo
{
    public function id();
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

class FooRab implements Foo
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
    public function __construct()
    {
        echo __METHOD__ . '(' . substr(json_encode(func_get_args()), 1, -1) . ")\n";
    }

    public function make()
    {
        echo __METHOD__ . '(' . substr(json_encode(func_get_args()), 1, -1) . ")\n";

        return new FooBar(300);
    }
}

$callback = function (Foo $foo, FooRab $foo_rab) {
    echo __METHOD__ . '(' . substr(json_encode(func_get_args()), 1, -1) . ")\n";

    echo $foo_rab->id() . ', ' . $foo->id() . "\n";
};

$container = new Container();

$container->instance(__NAMESPACE__ . '\Foo', new FooBar(1));

$container->instance(__NAMESPACE__ . '\FooRab', new FooRab(-1000));

$container->call($callback);

$container->withInstance(__NAMESPACE__ . '\Foo', new FooBar(100))->call($callback);

$container->withCallback(__NAMESPACE__ . '\Foo', function () {
    return new FooBar(200);
})->call($callback);

$container->withFactory(__NAMESPACE__ . '\Foo', __NAMESPACE__ . '\FooBarFactory', 'make')->call($callback);
