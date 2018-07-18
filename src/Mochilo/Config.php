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
        if (isset($this->values[$index])) {
            return $this->values[$index];
        }
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
