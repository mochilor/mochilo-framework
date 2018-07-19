<?php

namespace Mochilo;

use AltoRouter;
use DI\Container;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Twig_Loader_Filesystem;
use Twig_LoaderInterface;

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

        $dependencies = array_merge($this->getFrameworkDependencies(), $this->appDependencies);

        $container = $this->createContainer($dependencies);

        return $container->get('Mochilo\App')->run();
    }

    private function getFrameworkDependencies()
    {
        return [
            Config::class => \DI\autowire()->constructorParameter('configPath', $this->paths['configPath']),
            Data::class => \DI\autowire()->constructorParameter('dataPath', $this->paths['dataPath']),
            //Twig_Loader_Filesystem::class => \DI\autowire()->constructorParameter('paths', $paths['templatesPath']),
            Twig_LoaderInterface::class => \DI\create(Twig_Loader_Filesystem::class)
                ->constructor($this->paths['templatesPath']),
            AltoRouter::class => \DI\autowire()->constructorParameter('routes', $this->routes),
        ];
    }

    private function createContainer(array $dependencies): Container
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions($dependencies);
        return $containerBuilder->build();
    }
}
