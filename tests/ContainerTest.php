<?php

namespace Tests;

use Booth\Container;
use Booth\NotFoundException;
use PHPUnit\Framework\Attributes\Test;

class ContainerTest
{
    #[Test]
    public function test_register(): void
    {
        $container = new Container();

        $container->register('1', new Dependency(1));

        $this->assertTrue($container->has('1'));
    }

    #[Test]
    public function test_has(): void
    {
        $container = new Container();

        $container->register('1', new Dependency(1));
        $container->register('2', new Dependency(2));

        $this->assertTrue($container->has('1'));
        $this->assertTrue($container->has('2'));
        $this->assertFalse($container->has('3'));
    }

    #[Test]
    public function test_get(): void
    {
        $container = new Container();

        $container->register('1', new Dependency(1));
        $container->register('2', new Dependency(2));

        $this->assertSame(1, $container->get('1')->id);
        $this->assertSame(2, $container->get('2')->id);
    }

    #[Test]
    public function test_non_singleton(): void
    {
        $container = new Container();

        $container->register('1', fn () => new Dependency(1));

        $this->assertNotSame($container->get('1'), $container->get('1'));
    }

    #[Test]
    public function test_dependency_not_found(): void
    {
        $container = new Container();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Dependency not found: 1');

        $container->get('1');
    }

    #[Test]
    public function test_singleton(): void
    {
        $container = new Container();

        $container->singleton('1', fn () => new Dependency(1));

        $this->assertSame($container->get('1'), $container->get('1'));
    }

    #[Test]
    public function test_forget(): void
    {
        $container = new Container();

        $container->register('1', fn () => new Dependency(1));
        $container->forget('1');

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Dependency not found: 1');

        $container->get('1');
    }
}
