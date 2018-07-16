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
        $configFile = $configPath . '/config_values.php';
        if (file_exists($configFile)) {
            $this->values = require_once $configFile;
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

    public function set(string $index, $value)
    {
        $this->values[$index] = $value;
    }
}