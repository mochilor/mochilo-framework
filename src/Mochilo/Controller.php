<?php

namespace Mochilo;

use Twig_Environment;

class Controller
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

    public function notFound(): string
    {
        $this->code = 404;
        return '';
    }
}
