<?php

declare(strict_types=1);

namespace gdianov\opium;

/**
 * Class Config
 * @package gdianov\opium
 */
class Config implements ConfigInterface
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
     * @var array
     */
    private $props = [];

    /**
     * Config constructor.
     * @param array $config
     * @throws ConfigureException
     */
    public function __construct(array $config)
    {
        if (!isset($config['class'])) {
            throw new ConfigureException('Class name is not set.');
        }

        $this->class = $config['class'];

        if (isset($config['constructor'])) {
            $this->constructorParams = $config['constructor'];
        }

        if (isset($config['props'])) {
            $this->props = $config['props'];
        }
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