<?php

declare(strict_types=1);

namespace gdianov\opium;

use Psr\Container\ContainerInterface;

/**
 * Class Opium
 * @package gdianov\opium
 */
class Opium
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ConfigStorage
     */
    private $configStorage;

    /**
     * Opium constructor.
     * @param OpiumContainerInterface $container
     * @param ConfigStorage $storage
     */
    private function __construct(OpiumContainerInterface $container, ConfigStorage $storage)
    {
        $this->container = $container;
        $this->configStorage = $storage;
    }

    /**
     * @param OpiumContainerInterface $container
     * @param ConfigStorage $storage
     * @return Opium
     */
    public static function getInstance(OpiumContainerInterface $container, ConfigStorage $storage): Opium
    {
        return new Opium($container, $storage);
    }

    /**
     * @param string $service
     * @return object
     * @throws MakeInstanceException
     */
    public function make(string $service): object
    {
        $config = $this->configStorage[$service];
        if ($config === null) {
            throw new MakeInstanceException("$service is not exists.");
        }
        $creator = new ObjectCreator($config, $this);
        $object = $creator->create();
        $this->container->set($service, $object);
        return $object;
    }

    /**
     * @param array $params
     * @return object
     * @throws ConfigureException
     * @throws MakeInstanceException
     */
    public function makeDynamic($params = []) : object
    {
        $config = DynamicConfig::createConfig($params);
        $creator = new ObjectCreator($config, $this);
        return $creator->create();
    }

    /**
     * @param string $service
     * @param array $params
     * @return object|null
     * @throws ConfigureException
     * @throws MakeInstanceException
     */
    public function getWithParams(string $service, array $params) : ?object
    {
        /** @var ConfigInterface $config */
        $config = $this->configStorage[$service];
        if ($config === null) {
            throw new MakeInstanceException("$service is not exists.");
        }
        $params = array_merge(['class' => $config->getClass()], $params);
        return $this->makeDynamic($params);
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}