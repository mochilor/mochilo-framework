<?php

namespace Mochilo\Controller;

use Mochilo\Config;
use Mochilo\Data;
use Twig_Environment;

class PostController implements ControllerInterface
{
    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var Data
     */
    protected $data;

    /**
     * @var int
     */
    protected $code = 200;

    /**
     * MisterProper constructor.
     *
     * @param Twig_Environment $twig
     * @param Config $config
     * @param Data $data
     */
    public function __construct(Twig_Environment $twig, Config $config, Data $data)
    {
        $this->twig = $twig;
        $this->config = $config;
        $this->data = $data;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    protected function sanitizeInputData(array $data, array $validKeys)
    {
        $validData = [];
        foreach ($validKeys as $validKey) {
            $validData[$validKey] = '';
        }

        foreach ($data as $key => $value) {
            if (isset($validData[$key])) {
                $validData[$key] = $value;
            }
        }

        return $validData;
    }

    protected function getJsonData(string $message, array $errors = [])
    {
        $data = [
            'message' => $message,
            'errors' => $errors,
        ];

        return json_encode($data);
    }
}