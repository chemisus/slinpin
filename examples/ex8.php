<?php

namespace Example8;

use Slinpin\Container as Container;
use Slinpin\ProviderContext;

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
        echo __METHOD__ . '(' . substr(json_encode(func_get_args()), 1, -1) . ")\n";

        $this->id = $id;
    }

    public function id()
    {
        return $this->id;
    }
}

class FooContextGuest implements ProviderContext
{
    public function provide(Container $container)
    {
        return new FooBar(0);
    }

    public function applies(Container $container)
    {
        return $container->provide('User')->role() === 'guest';
    }
}

class FooContextAdmin implements ProviderContext
{
    public function provide(Container $container)
    {
        return new FooBar(1000);
    }

    public function applies(Container $container)
    {
        return $container->provide('User')->role() === 'admin';
    }
}

class User
{
    private $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function role()
    {
        return $this->role;
    }
}

$container = new Container();

$container->callback(__NAMESPACE__ . '\Foo', new FooBar(1))
    ->context(new FooContextGuest())
    ->context(new FooContextAdmin());

$container->withInstance('User', new User('admin'))->call(function (Foo $foo) {
    echo $foo->id() . "\n";
});

$container->withInstance('User', new User('guest'))->call(function (Foo $foo) {
    echo $foo->id() . "\n";
});
