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
            'config' => $this->config->getAll(),
            'helper' => new Helper($this->config),
        ];
    }
}
