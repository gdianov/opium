<?php

declare(strict_types=1);

namespace gdianov\opium\tests;

use gdianov\opium\Config;
use gdianov\opium\ConfigStorage;
use gdianov\opium\ConfigureException;
use PHPUnit\Framework\TestCase;
use gdianov\opium\DynamicConfig;
use gdianov\opium\tests\classes\T;

/**
 * Class ConfigTest
 * @package gdianov\opium\tests
 */
class ConfigTest extends TestCase
{
    /**
     * @test
     * @throws ConfigureException
     */
    public function create_config()
    {
        $config = new Config([
            'class' => T::class,
            'constructor' => [5],
            'props' => [
                ['bar' => 'barValue']
            ]
        ]);

        $this->assertEquals(T::class, $config->getClass());
        $this->assertEquals([5], $config->getConstructorParams());
        $this->assertEquals([['bar' => 'barValue']], $config->getProps());
    }

    /**
     * @test
     * @throws ConfigureException
     */
    public function create_dynamic_config()
    {
        $config = DynamicConfig::createConfig([
            'class' => T::class,
            'constructor' => [5],
                'props' => [
                ['bar' => 'barValue']
            ]
        ]);

        $this->assertEquals(T::class, $config->getClass());
        $this->assertEquals([5], $config->getConstructorParams());
        $this->assertEquals([['bar' => 'barValue']], $config->getProps());
    }

    /**
     * @test
     * @throws ConfigureException
     */
    public function create_failed_config()
    {
        $this->expectException(ConfigureException::class);
        $this->expectExceptionMessage('Class name is not set.');
        new Config([
            'constructor' => [5],
            'props' => [
                ['bar' => 'barValue']
            ]
        ]);
    }

    /**
     * @test
     * @throws ConfigureException
     */
    public function create_failed_dynamic_config()
    {
        $this->expectException(ConfigureException::class);
        $this->expectExceptionMessage('Class name is not set.');
        DynamicConfig::createConfig([
            'constructor' => [5],
            'props' => [
                ['bar' => 'barValue']
            ]
        ]);
    }

    /**
     * @test
     * @throws ConfigureException
     */
    public function save_in_storage()
    {
        $storage = new ConfigStorage();
        $config = new Config(['class' => T::class]);
        $storage['@t'] = $config;
        $this->assertArrayHasKey('@t', $storage);
        $this->assertSame($config, $storage['@t']);
        $this->assertEquals(null, $storage['@test']);
    }
}