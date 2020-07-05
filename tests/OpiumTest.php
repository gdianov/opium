<?php

declare(strict_types=1);

namespace gdianov\opium\tests;

use gdianov\opium\Container;
use gdianov\opium\MakeInstanceException;
use gdianov\opium\Opium;
use gdianov\opium\YamlLoader;
use PHPUnit\Framework\TestCase;
use gdianov\opium\tests\classes\T;
use gdianov\opium\tests\classes\C;
use gdianov\opium\tests\classes\P;

/**
 * Class OpiumTest
 * @package gdianov\opium\tests
 */
class OpiumTest extends TestCase
{
    /**
     * @var Opium
     */
    private $opium;

    /**
     * @throws \gdianov\opium\ConfigureException
     * @throws \gdianov\opium\FileNotFoundException
     */
    public function setUp(): void
    {
        $configFile = __DIR__ . '/config.yaml';
        $loader = new YamlLoader($configFile);
        $config = $loader->configure();
        $this->opium = Opium::getInstance(new Container(), $config);
    }

    /**
     * @test
     * @throws \gdianov\opium\MakeInstanceException
     */
    public function make_object_by_service_name()
    {
        $t = $this->opium->make('t');
        $this->assertInstanceOf(T::class, $t);

        //T object contains in container
        $t = $this->opium->getContainer()->get('t');
        $this->assertInstanceOf(T::class, $t);
    }

    /**
     * @test
     * @throws \gdianov\opium\ConfigureException
     * @throws \gdianov\opium\MakeInstanceException
     */
    public function make_object_by_dynamic()
    {
        $barValue = 'barValue';
        /** @var T $object */
        $t = $this->opium->makeDynamic([
            'class' => T::class,
            'props' => [
                ['bar' => $barValue]
            ],
        ]);

        $this->assertEquals($barValue,  $t->bar);
    }

    /**
     * @test
     * @throws \gdianov\opium\MakeInstanceException
     */
    public function make_object_inject_by_constructor()
    {
        /** @var C $c */
        $c = $this->opium->make('c');
        $this->assertInstanceOf(C::class, $c);
        $this->assertInstanceOf(T::class, $c->getT());
        $this->assertEquals('barValue', $c->getT()->bar);
    }

    /**
     * @test
     * @throws \gdianov\opium\MakeInstanceException
     */
    public function make_object_inject_by_prop()
    {
        /** @var P $p */
        $p = $this->opium->make('p');
        $this->assertInstanceOf(P::class, $p);
        $c = $p->c;
        $this->assertInstanceOf(C::class, $c);
        $t = $c->getT();
        $this->assertInstanceOf(T::class, $t);
        $this->assertEquals($p->getStr(), 'opium');
    }

    /**
     * @test
     * @throws MakeInstanceException
     * @throws \gdianov\opium\ConfigureException
     */
    public function make_object_with_params()
    {
        /** @var T $t */
        $t = $this->opium->getWithParams('t', [
            'props' => [
                ['bar' => 'someBar'],
            ]
        ]);
        $this->assertInstanceOf(T::class, $t);
        $this->assertEquals('someBar', $t->bar);
    }

    /**
     * @test
     * @throws MakeInstanceException
     */
    public function try_make_instance_by_not_exists_name()
    {
        $this->expectException(MakeInstanceException::class);
        $this->expectExceptionMessage('pct is not exists.');
        $this->opium->make('pct');
    }

    /**
     * @test
     * @throws MakeInstanceException
     * @throws \gdianov\opium\ConfigureException
     */
    public function try_set_not_exists_property()
    {
        $this->expectException(MakeInstanceException::class);
        $this->expectExceptionMessage('Property baz does not exist');
        $this->opium->makeDynamic([
            'class' => T::class,
            'props' => [
                ['baz' => 'baz']
            ],
        ]);
    }

    /**
     * @test
     * @throws MakeInstanceException
     * @throws \gdianov\opium\ConfigureException
     */
    public function try_set_private_property()
    {
        $this->expectException(MakeInstanceException::class);
        $this->expectExceptionMessage('Property foo is not public');
        $this->opium->makeDynamic([
            'class' => T::class,
            'props' => [
                ['foo' => 1]
            ],
        ]);
    }
}