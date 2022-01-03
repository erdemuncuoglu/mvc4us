<?php

declare(strict_types=1);


namespace Mvc4us\Routing\Loader;

use Symfony\Component\Routing\Loader\AnnotationClassLoader;
use Symfony\Component\Routing\Route;

class AnnotatedRouteControllerLoader extends AnnotationClassLoader
{

    protected function configureRoute(Route $route, \ReflectionClass $class, \ReflectionMethod $method, object $annot)
    {
        if ($method->getName() === '__invoke' || $method->getName() === 'handle') {
            $route->setDefault('_controller', $class->getName());
        } else {
            $route->setDefault('_controller', $class->getName() . '::' . $method->getName());
        }
    }
}
