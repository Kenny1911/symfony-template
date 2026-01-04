<?php

declare(strict_types=1);

namespace App\File\Shared;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->set(PathNormalizer::class);
};
