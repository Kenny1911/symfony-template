<?php

declare(strict_types=1);

namespace App\File\Storage;

use App\File\Shared\PathNormalizer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->set(LocalFileStorage::class)
            ->args([
                ((string) param('kernel.project_dir')) . '/uploads',
                service(PathNormalizer::class),
                service('clock'),
            ])
        ->alias(FileStorage::class, LocalFileStorage::class);
};
