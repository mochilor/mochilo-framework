<?php

namespace Mochilo;

class Config
{
    /**
     * @var array
     */
    private $values = [];

    /**
     * Config constructor.
     *
     * @param string $configPath
     */
    public function __construct(string $configPath)
    {
        $defaultConfigFile = __DIR__  . '/config/default_config_values.php';
        $this->values = require_once $defaultConfigFile;

        $configFile = $configPath . '/config_values.php';
        if (file_exists($configFile)) {
            $configValues = require_once $configFile;
            $this->values = array_merge($this->values, $configValues);
        }
    }

    /**
     * @param string $index
     * @return mixed
     */
    public function get(string $index)
    {
        $path = explode('.', $index);
        $currentElement = $this->values;

        do {
            if (!isset($currentElement[$path[0]])) {
                return null;
            }

            $currentElement = $currentElement[$path[0]];

            unset($path[0]);
            $path = array_values($path);
        } while (!empty($path));

        return $currentElement;
    }

    public function getAll(): array
    {
        return $this->values;
    }

    public function set(string $index, $value)
    {
        $this->values[$index] = $value;
    }
}
