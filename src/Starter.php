<?php

namespace Mochilo;

use AltoRouter;
use DI\Container;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Mochilo\Mail\DummyMailer;
use Mochilo\Mail\MailerInterface;
use Mochilo\Mail\NativeMailer;
use Mochilo\Mail\SMTPMailer;
use PHPMailer\PHPMailer\PHPMailer;

class Starter
{
    /**
     * @var array
     */
    private $paths;
    /**
     * @var array
     */
    private $routes;
    /**
     * @var array
     */
    private $appDependencies;

    public function __construct(array $paths, array $routes, array $appDependencies = [])
    {
        $this->paths = $paths;
        $this->routes = $routes;
        $this->appDependencies = $appDependencies;
    }

    public function startApplication()
    {
        $dotenv = new Dotenv($this->paths['envPath']);
        $dotenv->load();

        $this->setErrorLevel();

        $dependencies = array_merge($this->getFrameworkDependencies(), $this->appDependencies);
        $container = $this->createContainer($dependencies);

        return $container->get('Mochilo\App')->run();
    }

    private function getFrameworkDependencies()
    {
        return [
            // Autowiring
            Config::class => \DI\autowire()->constructorParameter('configPath', $this->paths['configPath']),
            Data::class => \DI\autowire()->constructorParameter('dataPath', $this->paths['dataPath']),
            AltoRouter::class => \DI\autowire()->constructorParameter('routes', $this->routes),
            Helper::class => \DI\autowire()->constructorParameter(
                'config',
                \DI\create(Config::class)->constructor($this->paths['configPath'])
            ),

            // Interfaces implementation
            \Twig\Loader\LoaderInterface::class => \DI\create(\Twig\Loader\FilesystemLoader::class)
                ->constructor($this->paths['templatesPath']),
            MailerInterface::class => $this->getMailerImplementation(),
        ];
    }

    private function createContainer(array $dependencies): Container
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions($dependencies);
        return $containerBuilder->build();
    }

    private function setErrorLevel()
    {
        if (filter_var(getenv('DEBUG'), FILTER_VALIDATE_BOOLEAN)) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }
    }

    private function getMailerImplementation()
    {
        $mailDriver = filter_var(getenv('MAIL_DRIVER'));
        if ($mailDriver == 'smtp') {
            return \DI\create(SMTPMailer::class)->constructor(\DI\create(PHPMailer::class));
        } elseif ($mailDriver == 'native') {
            return \DI\create(NativeMailer::class);
        }

        return \DI\create(DummyMailer::class);
    }
}
