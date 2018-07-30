<?php

namespace Mochilo\Controller;

use Mochilo\Config;
use Mochilo\Data;
use Mochilo\Helper;
use Twig_Environment;

class GetController implements ControllerInterface
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
     * @var Helper
     */
    protected $helper;

    /**
     * MisterProper constructor.
     *
     * @param Twig_Environment $twig
     * @param Config $config
     * @param Data $data
     * @param Helper $helper
     */
    public function __construct(Twig_Environment $twig, Config $config, Data $data, Helper $helper)
    {
        $this->twig = $twig;
        $this->config = $config;
        $this->data = $data;
        $this->helper = $helper;
    }

    /**
     * @return int
     */
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
