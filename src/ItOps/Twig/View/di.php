<?php

declare(strict_types=1);

namespace App\ItOps\Twig\View;

use Kenny1911\TwigView\AttributeViewRenderer;
use Kenny1911\TwigView\ChainRenderer;
use Kenny1911\TwigView\Symfony\ViewListener;
use Kenny1911\TwigView\ViewRenderer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\inline_service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->set('twig.view_renderer', ChainRenderer::class)
            ->args([
                [
                    inline_service(ViewRenderer::class)
                        ->args([
                            service('twig'),
                        ]),
                    inline_service(AttributeViewRenderer::class)
                        ->args([
                            service('twig'),
                        ]),
                ],
            ])

        ->set(ViewListener::class)
            ->args([
                service('twig.view_renderer'),
            ])
            ->tag('kernel.event_subscriber');
};
