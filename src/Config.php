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
        $this->values = require $defaultConfigFile;

        $configFile = $configPath . '/config_values.php';
        if (file_exists($configFile)) {
            $configValues = require $configFile;
            $this->values = $this->mergeConfigValues($this->values, $configValues);
        }
    }

    /**
     * @see http://php.net/manual/es/function.array-merge-recursive.php#92195
     * @param array $array1
     * @param array $array2
     * @return array
     */
    private function mergeConfigValues(array $array1, array $array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset ($merged [$key]) && is_array($merged [$key])) {
                $merged [$key] = $this->mergeConfigValues($merged [$key], $value);
            } else {
                $merged [$key] = $value;
            }
        }

        return $merged;
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
