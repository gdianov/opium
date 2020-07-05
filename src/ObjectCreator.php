<?php

declare(strict_types=1);

namespace gdianov\opium;

use ReflectionClass;

/**
 * Class ObjectCreator
 * @package gdianov\opium
 */
class ObjectCreator
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var Opium
     */
    private $opium;

    /**
     * Instance constructor.
     * @param ConfigInterface $config
     * @param Opium $opium
     */
    public function __construct(ConfigInterface $config, Opium $opium)
    {
        $this->config = $config;
        $this->opium = $opium;
    }

    /**
     * @return object
     * @throws MakeInstanceException
     */
    public function create(): object
    {
        try {
            $class = $this->config->getClass();
            $r = new ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw new MakeInstanceException('Can\'t create instance.');
        }

        $constructorParams = [];
        foreach ($this->config->getConstructorParams() as $constructorParam) {
            if (is_string($constructorParam)) {
                $constructorParams[] = $this->getDependencyValue($constructorParam);
                continue;
            }
            $constructorParams[] = $constructorParam;
        }

        if (!empty($constructorParams)) {
            $object = $r->newInstanceArgs($constructorParams);
        } else {
            $object = $r->newInstanceWithoutConstructor();
        }

        foreach ($this->config->getProps() as $props) {
            foreach ($props as $prop => $value) {
                try {
                    $property = $r->getProperty($prop);

                    if (!property_exists($object, $prop)) {
                        throw new MakeInstanceException("Property $prop does not exist");
                    }

                    if (!$property->isPublic()) {
                        throw new MakeInstanceException("Property $prop is not public");
                    }

                    if (is_string($value)) {
                        $property->setValue($object, $this->getDependencyValue($value));
                    } else {
                        $property->setValue($object, $value);
                    }

                } catch (\ReflectionException $e) {
                    throw new MakeInstanceException($e->getMessage());
                }
            }
        }

        return $object;
    }

    /**
     * @param string $dependencyName
     * @return object|string
     * @throws MakeInstanceException
     */
    private function getDependencyValue(string $dependencyName)
    {
        if (substr($dependencyName, 0 , 1) === '@') {
            return $this->opium->make(substr($dependencyName, 1));
        } elseif (class_exists($dependencyName)) {
            return new $dependencyName;
        }

        return $dependencyName;
    }
}