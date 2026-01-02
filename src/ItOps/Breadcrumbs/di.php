<?php

declare(strict_types=1);

namespace App\ItOps\Breadcrumbs;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->set(BreadcrumbsBuilder::class)
            ->args([
                service(RequestStack::class),
                service(UrlGeneratorInterface::class),
            ]);
};
