<?php

declare(strict_types=1);

namespace gdianov\opium;

/**
 * Interface ConfigInterface
 * @package gdianov\opium
 */
interface ConfigInterface
{
    /**
     * @return string
     */
    public function getClass(): string;

    /**
     * @return array
     */
    public function getConstructorParams(): array;

    /**
     * @return array
     */
    public function getProps(): array;
}