<?php

declare(strict_types=1);

namespace gdianov\opium\tests;

use gdianov\opium\Container;
use gdianov\opium\NotFoundException;
use PHPUnit\Framework\TestCase;
use gdianov\opium\tests\classes\T;
use InvalidArgumentException;

/**
 * Class ContainerTest
 * @package gdianov\opium\tests
 */
class ContainerTest extends TestCase
{
    /**
     * @test
     */
    public function should_set_object_and_has_it()
    {
        $container = new Container();
        $container->set('@t', new T(1));
        $this->assertEquals( true, $container->has('@t'));
    }

    /**
     * @test
     * @throws \gdianov\opium\NotFoundException
     */
    public function should_get_object()
    {
        $container = new Container();
        $expected = new T(2);
        $container->set('@t', $expected);
        $actual = $container->get('@t');
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @throws \gdianov\opium\NotFoundException
     */
    public function should_throw_get_object()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('No entry or class found for @test');
        $container = new Container();
        $expected = new T(3);
        $container->set('@t', $expected);
        $container->get('@test');
    }

    /**
     * @test
     */
    public function try_pass_not_string()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The name parameter must be of type string, integer given');
        $container = new Container();
        $container->has(0);
    }
}