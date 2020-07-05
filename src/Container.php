<?php

declare(strict_types=1);

namespace gdianov\opium;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;

/**
 * Class Container
 * @package gdianov\opium
 */
class Container implements OpiumContainerInterface, ContainerInterface
{
    /**
     * @var object[]
     */
    private $container = [];

    /**
     * @param string $service
     * @param object $object
     */
    public function set(string $service, object $object) : void
    {
        $this->container[$service] = $object;
    }

    /**
     * @param string $id
     * @return mixed|object|null
     * @throws NotFoundException
     */
    public function get($id)
    {
        $value = $this->container[$id] ?? null;
        if ($value === null) {
            throw new NotFoundException("No entry or class found for $id");
        }

        return $value;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        if (! is_string($id)) {
            throw new InvalidArgumentException(sprintf(
                'The name parameter must be of type string, %s given',
                is_object($id) ? get_class($id) : gettype($id)
            ));
        }

        if (array_key_exists($id, $this->container)) {
            return true;
        }

        return false;
    }
}