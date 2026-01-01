<?php

declare(strict_types=1);

namespace App\ItOps\Doctrine\Hydrator;

use Kenny1911\DoctrineDbalHydrator\Hydrator;
use Kenny1911\DoctrineDbalHydrator\Mapping\AttributeLoader;
use Kenny1911\DoctrineDbalHydrator\ObjectHydrator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\inline_service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->set(Hydrator::class)
            ->args([
                inline_service(ObjectHydrator::class)
                    ->factory([null, 'createByConnection'])
                    ->args([
                        service('doctrine.dbal.default_connection'),
                    ]),
                inline_service(AttributeLoader::class),
            ]);
};
