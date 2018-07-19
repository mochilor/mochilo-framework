<?php

namespace Mochilo\Controller;

use Mochilo\Config;
use Mochilo\Data;
use PHPMailer\PHPMailer\PHPMailer;

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
     * @var PHPMailer
     */
    private $mailer;

    /**
     * MisterProper constructor.
     *
     * @param Config $config
     * @param Data $data
     * @param PHPMailer $mailer
     */
    public function __construct(Config $config, Data $data, PHPMailer $mailer)
    {
        $this->config = $config;
        $this->data = $data;
        $this->mailer = $mailer;
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