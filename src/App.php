<?php

namespace Mochilo;

use AltoRouter;
use DI\Container;
use Mochilo\Controller\ControllerInterface;
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

    const TEMPLATES_DIR = 'templates';
    /**
     * @var Container
     */
    private $container;

    /**
     * App constructor.
     *
     * @param Config $config
     * @param AltoRouter $router
     * @param Twig_Environment $twig
     * @param Data $data
     */
    public function __construct(Config $config, AltoRouter $router, Twig_Environment $twig, Data $data, Container $container)
    {
        $this->config = $config;
        $this->router = $router;
        $this->twig = $twig;
        $this->data = $data;
        $this->container = $container;
    }

    public function run()
    {
        $this->data->prepareData($this->config->get('lang'));
        $this->addDefaultTemplatePath();
        $output = $this->twig->render("not_found.twig");
        $code = 404;
        $match = $this->router->match();

        if ($match) {
            $controller = $this->parseController($match);
            $method = $this->parseMethod($match);
            $params = $_POST ?? $match['params'];

            try {
                if ($controller instanceof ControllerInterface && method_exists($controller, $method)) {
                    $output = call_user_func(array($controller, $method), $params);
                    $code = $controller->getCode();
                }
            } catch (\Exception $e) {
                $output = $this->getError($e);
                $code = 500;
            }
        }

        $this->output($output, $code);
    }

    private function addDefaultTemplatePath()
    {
        $loader = $this->twig->getLoader();
        $loader->addPath(dirname(__FILE__) . '/' . self::TEMPLATES_DIR);
    }

    private function parseController($match)
    {
        $class = substr($match['target'], 0, strpos($match['target'], '#'));
        if (class_exists($class) && in_array(ControllerInterface::class, class_implements($class))) {
            return $this->container->get($class);
        }
    }

    private function parseMethod($match) :string
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
