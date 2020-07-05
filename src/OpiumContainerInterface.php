<?php

declare(strict_types=1);

namespace gdianov\opium;

/**
 * Interface OpiumContainerInterface
 * @package gdianov\opium
 */
interface OpiumContainerInterface
{
    /**
     * @param string $service
     * @param object $object
     */
    public function set(string $service, object $object) : void;
}