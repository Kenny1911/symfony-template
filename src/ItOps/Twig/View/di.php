<?php

declare(strict_types=1);

namespace App\ItOps\Twig\View;

use Kenny1911\TwigView\Symfony\ViewListener;
use Kenny1911\TwigView\ViewRenderer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->set('twig.view_renderer', ViewRenderer::class)
            ->args([
                service('twig'),
            ])

        ->set(ViewListener::class)
            ->args([
                service('twig.view_renderer'),
            ])
            ->tag('kernel.event_subscriber');
};
