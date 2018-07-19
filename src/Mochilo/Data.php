<?php

namespace Mochilo;

class Data
{
    /**
     * @var string
     */
    private $lang;

    /**
     * @var string
     */
    private $dataPath;

    /**
     * @var array
     */
    private $data = [];

    /**
     * Data constructor.
     *
     * @param string $dataPath
     */
    public function __construct(string $dataPath)
    {
        $this->dataPath = $dataPath;
    }

    public function prepareData(string $lang)
    {
        $this->lang = $lang;

        $file = sprintf($this->dataPath . '/%s.php', $lang);

        if (file_exists($file)) {
            $this->data = require $file;
        }
    }

    public function getData()
    {
        return $this->data;
    }
}
