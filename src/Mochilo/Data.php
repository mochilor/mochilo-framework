<?php

namespace Mochilo;

class Data
{
    /**
     * @var string
     */
    private $defaultLang;

    /**
     * @var string
     */
    private $dataPath;

    /**
     * @var array
     */
    private $data;

    /**
     * Data constructor.
     *
     * @param string $defaultLang
     * @param string $dataPath
     */
    public function __construct(string $defaultLang, string $dataPath)
    {
        $this->defaultLang = $defaultLang;
        $this->dataPath = $dataPath;
        $this->prepareData();
    }

    public function prepareData(string $lang = null)
    {
        if (!empty($lang)) {
            $this->defaultLang = $lang;
        }

        $file = sprintf($this->dataPath . '/%s.php', $this->defaultLang);

        if (file_exists($file)) {
            $this->data = require $file;
        }
    }

    public function getData()
    {
        return $this->data;
    }
}
