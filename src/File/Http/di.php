<?php

declare(strict_types=1);

namespace App\File\Http;

use App\File\Shared\PathNormalizer;
use App\File\Storage\FileStorage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->set(BinaryFileHttpHandler::class)
            ->args([
                service(FileStorage::class),
            ])
        ->set(XAccelRedirectFileHttpHandler::class)
            ->args([
                service(FileStorage::class),
                service(PathNormalizer::class),
                '/__protected_uploads__/',
            ])
//        ->alias(FileHttpHandler::class, BinaryFileHttpHandler::class);
        ->alias(FileHttpHandler::class, XAccelRedirectFileHttpHandler::class);
};
