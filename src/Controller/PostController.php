<?php

namespace Mochilo\Controller;

use Mochilo\Config;
use Mochilo\Data;
use Mochilo\Exception\CSRFTokenException;

class PostController implements ControllerInterface
{
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
     * @param Config $config
     * @param Data $data
     */
    public function __construct(Config $config, Data $data)
    {
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

    /**
     * @param string $token
     * @throws CSRFTokenException
     */
    protected function validateToken(string $token)
    {
        if (!empty($_SESSION) && isset($_SESSION['token']) && $token != $_SESSION['token']) {
            throw new CSRFTokenException();
        }
    }
}