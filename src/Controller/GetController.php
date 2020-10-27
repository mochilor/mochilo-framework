<?php

namespace Mochilo\Controller;

use Mochilo\Config;
use Mochilo\Data;
use Mochilo\Helper;
use Twig\Environment;

class GetController implements ControllerInterface
{
    protected $twig;
    protected $config;
    protected $data;
    protected $code = 200;
    protected $helper;

    public function __construct(Environment $twig, Config $config, Data $data, Helper $helper)
    {
        $this->twig = $twig;
        $this->config = $config;
        $this->data = $data;
        $this->helper = $helper;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    protected function getDataForView(): array
    {
        return [
            'data' => $this->data->getData(),
            'config' => $this->config,
            'helper' => $this->helper,
        ];
    }
}
