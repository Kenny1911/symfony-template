<?php

declare(strict_types=1);

namespace App\Gateway\Home;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->set(Controller::class)
            ->public();
};
