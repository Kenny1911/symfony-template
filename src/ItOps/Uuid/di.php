<?php

declare(strict_types=1);

namespace App\ItOps\Uuid;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->set(UuidV4Generator::class)
        ->set(UuidV7Generator::class)
            ->args([
                service('clock'),
            ])
        ->alias(UuidGenerator::class, UuidV7Generator::class)
        ->alias('uuid.generator', UuidV7Generator::class);
};
