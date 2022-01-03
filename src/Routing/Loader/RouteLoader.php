<?php

declare(strict_types=1);

namespace Mvc4us\Routing\Loader;

use Mvc4us\Routing\NonRedirectingCompiledUrlMatcher;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Routing\Loader\AnnotationClassLoader;
use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\Loader\PhpFileLoader;

/**
 *
 * @author erdem
 */
final class RouteLoader
{

    /**
     * This class should not be instantiated.
     */
    private function __construct()
    {}

    public static function load($projectDir): Router
    {
        $routeLocator = new FileLocator($projectDir . '/config/routes');
        $routeLoader = new PhpFileLoader($routeLocator);

        $resolver = new LoaderResolver();
        $resolver->addLoader(new AnnotationDirectoryLoader(new FileLocator($projectDir), new AnnotatedRouteControllerLoader()));
        $routeLoader->setResolver($resolver);

        // TODO: Make redirection configurable
        $router = new Router(
            $routeLoader,
            'routes.php',
            [
                'matcher_class' => NonRedirectingCompiledUrlMatcher::class
                // 'matcher_class' => RedirectingCompiledUrlMatcher::class
            ]);
        return $router;
    }
}

