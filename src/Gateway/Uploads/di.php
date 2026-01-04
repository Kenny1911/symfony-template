<?php

declare(strict_types=1);

namespace App\Gateway\Uploads;

use App\File\Http\FileHttpHandler;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->set(Controller::class)
            ->args([
                service(FileHttpHandler::class),
            ])
            ->public();
};
