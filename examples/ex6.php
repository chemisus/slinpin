<?php

namespace Example6;

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

    public function __construct()
    {
        echo __METHOD__ . '(' . substr(json_encode(func_get_args()), 1, -1) . ")\n";

        $this->id = 99;
    }

    public function id()
    {
        return $this->id;
    }
}

$container = new Container();

$container->call(function (FooBar $foo_bar) {
    echo __METHOD__ . '(' . substr(json_encode(func_get_args()), 1, -1) . ")\n";

    echo $foo_bar->id() . "\n";
});
