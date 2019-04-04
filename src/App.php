<?php

namespace Mochilo;

use AltoRouter;
use DI\Container;
use Mochilo\Controller\ControllerInterface;
use Twig\Environment;

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
     * @var Environment
     */
    private $twig;

    /**
     * @var Data
     */
    private $data;

    /**
     * @var Container
     */
    private $container;

    const TEMPLATES_DIR = 'templates';
    const SESSION_TIME = 1800;
    const COOKIE_NAME = 'cookies';

    /**
     * App constructor.
     *
     * @param Config $config
     * @param AltoRouter $router
     * @param Environment $twig
     * @param Data $data
     */
    public function __construct(Config $config, AltoRouter $router, Environment $twig, Data $data, Container $container)
    {
        $this->config = $config;
        $this->router = $router;
        $this->twig = $twig;
        $this->data = $data;
        $this->container = $container;
    }

    public function run()
    {
        $this->handleSession();
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

    private function handleSession()
    {
        if (!$this->config->get('set_cookies')) {
            return;
        }

        if (
            !empty($_SESSION) &&
            isset($_SESSION['LAST_ACTIVITY']) &&
            (time() - $_SESSION['LAST_ACTIVITY'] > self::SESSION_TIME)
        ) {
            session_unset();
            session_destroy();
        }

        session_start();
        $_SESSION['LAST_ACTIVITY'] = time();

        if (!isset($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
            $_SESSION['token_time'] = time();
        }

        $this->setCookie();
    }

    private function setCookie()
    {
        if (isset($_COOKIE[self::COOKIE_NAME])) {
            $this->twig->addGlobal('cookie', $_COOKIE[self::COOKIE_NAME]);
        }
        $expire = time() + 60 * 60 * 24 * 365;

        setcookie(self::COOKIE_NAME, 1, $expire, '/', '', false, true);
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
