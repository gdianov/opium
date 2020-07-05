<?php

namespace gdianov\opium;

use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlLoader
 * @package gdianov\opium
 */
final class YamlLoader
{
    /**
     * @var string
     */
    private $yamlFilePath;

    /**
     * YamlLoader constructor.
     * @param string $yamlFilePath
     */
    public function __construct(string $yamlFilePath)
    {
        $this->yamlFilePath = $yamlFilePath;
    }

    /**
     * @return ConfigStorage
     * @throws ConfigureException
     * @throws FileNotFoundException
     */
    public function configure(): ConfigStorage
    {
        $configStorage = new ConfigStorage();
        $configData = $this->getConfigData();
        foreach ($configData as $serviceName => $config) {
            $configStorage[$serviceName] = new Config($config);
        }

        return $configStorage;
    }

    /**
     * @return array
     * @throws FileNotFoundException
     */
    private function getConfigData() : array
    {
        if (!file_exists($this->yamlFilePath)) {
            throw new FileNotFoundException("File {$this->yamlFilePath} not found.");
        }
        return Yaml::parseFile($this->yamlFilePath);
    }
}