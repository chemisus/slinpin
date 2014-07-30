<?php

namespace Example3;

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

$container = new Container();

$id = 1;

$container->callback(__NAMESPACE__ . '\Foo', function () use (&$id) {
    echo __METHOD__ . '(' . substr(json_encode(func_get_args()), 1, -1) . ")\n";

    $id++;

    return new FooBar($id);
})->cache();

$container->call(function (Foo $foo) {
    echo $foo->id() . "\n";
});

$container->call(function (Foo $foo) {
    echo $foo->id() . "\n";
});

$container->call(function (Foo $foo) {
    echo $foo->id() . "\n";
});
