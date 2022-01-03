<?php

declare(strict_types=1);

namespace Mvc4us\Twig;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class TwigLoader
{

    private function __construct()
    {}

    public static function load($projectDir)
    {
        $loader = new FilesystemLoader($projectDir . '/templates');
        $twig = new Environment($loader, [
            'cache' => $projectDir . '/var/cache/twig',
            'auto_reload' => true // TODO
        ]);

        return $twig;
    }
}

