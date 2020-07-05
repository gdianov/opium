<?php

declare(strict_types=1);

namespace gdianov\opium;

/**
 * Class DynamicConfig
 * @package gdianov\opium
 */
final class DynamicConfig implements ConfigInterface
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var array
     */
    private $constructorParams = [];

    /**
     * @var
     */
    private $props;

    /**
     * DynamicConfig constructor.
     * @param string $class
     * @param array $constructorParams
     * @param array $props
     */
    private function __construct(
        string $class,
        array $constructorParams,
        array $props
    )
    {
        $this->class = $class;
        $this->constructorParams = $constructorParams;
        $this->props = $props;
    }

    /**
     * @param array $configParams
     * @return DynamicConfig
     * @throws ConfigureException
     */
    public static function createConfig(array $configParams) : DynamicConfig
    {
        if (!isset($configParams['class'])) {
            throw new ConfigureException('Class name is not set.');
        }
        $class = $configParams['class'];
        $constructorParams = $configParams['constructor'] ?? [];
        $props = $configParams['props'] ?? [];
        return new DynamicConfig($class, $constructorParams, $props);
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return array
     */
    public function getConstructorParams(): array
    {
        return $this->constructorParams;
    }

    /**
     * @return array
     */
    public function getProps(): array
    {
        return $this->props;
    }
}