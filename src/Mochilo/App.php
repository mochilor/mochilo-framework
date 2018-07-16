<?php

namespace Mochilo;

use AltoRouter;
use Twig_Environment;

class App
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var AltoRouter
     */
    private $router;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var Data
     */
    private $data;

    /**
     * App constructor.
     *
     * @param Config $config
     * @param AltoRouter $router
     * @param Twig_Environment $twig
     * @param Data $data
     */
    public function __construct(Config $config, AltoRouter $router, Twig_Environment $twig, Data $data)
    {
        $this->config = $config;
        $this->router = $router;
        $this->twig = $twig;
        $this->data = $data;
    }

    public function run()
    {
        $match = $this->router->match();

        if($match) {
            $controller = $this->parseController($match);
            $method = $this->parseMethod($match);

            $output = null;
            $code = 404;
            try {
                if ($controller instanceof Controller && method_exists($controller, $method)) {
                    $output = $controller->$method();
                    $code = $controller->getCode();
                } elseif ($controller instanceof Controller) {
                    $output = $controller->notFound();
                    $code = $controller->getCode();
                }
            } catch (\Exception $e) {
                $output = $this->getError($e);
                $code = 500;
            }

            $this->output($output, $code);
        }
    }

    private function parseController($match)
    {
        $class = substr($match['target'], 0, strpos($match['target'], '#'));
        if (class_exists($class) && is_subclass_of($class, Controller::class)) {
            return new $class($this->twig, $this->config, $this->data);
        }
    }

    private function parseMethod($match)
    {
        return substr($match['target'], strpos($match['target'], '#') + 1);
    }

    private function getError(\Exception $e)
    {
        if ($this->config->get('debug')) {
            return $e->getMessage();
        }
    }

    private function output(string $output, int $code)
    {
        http_response_code($code);
        echo $output;
    }
}