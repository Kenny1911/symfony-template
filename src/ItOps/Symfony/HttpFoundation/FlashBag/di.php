<?php

declare(strict_types=1);

namespace App\ItOps\Symfony\HttpFoundation\FlashBag;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\RequestStack;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->set(FlashBag::class)
            ->args([
                service(RequestStack::class),
            ]);
};
