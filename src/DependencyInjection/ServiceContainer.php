<?php

declare(strict_types=1);

namespace Mvc4us\DependencyInjection;

use Mvc4us\DependencyInjection\Loader\RouteServiceLoader;
use Mvc4us\DependencyInjection\Loader\SerializerServiceLoader;
use Mvc4us\DependencyInjection\Loader\TwigServiceLoader;
use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

/**
 * @author erdem
 * @internal
 */
final class ServiceContainer
{

    /**
     * This class should not be instantiated.
     */
    private function __construct()
    {
    }

    public static function load($projectDir): ContainerInterface
    {
        $container = new ContainerBuilder();
        $serviceLocator = new FileLocator($projectDir . '/config');
        $serviceLoader = new PhpFileLoader($container, $serviceLocator);

        try {
            $serviceLoader->load('services.php');
        } catch (FileLocatorFileNotFoundException $e) {
            $definition = new Definition();
            $definition->setAutowired(true)->setAutoconfigured(true)->setPublic(true);
            $serviceLoader->registerClasses($definition, 'App\\', $projectDir . '/src/*', null);
            error_log(
                "File '/config/services.php' not found. All container objects defined as public 'App\\' => '${projectDir}/src/*'."
            );
        }

        RouteServiceLoader::load($container, $projectDir);
        TwigServiceLoader::load($container, $projectDir);
        SerializerServiceLoader::load($container);

        $container->compile();
        return $container;
    }
}