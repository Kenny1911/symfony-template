<?php

declare(strict_types=1);

namespace App\User\Credentials;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->set(UserCredentialsManager::class)
            ->args([
                service('security.password_hasher_factory'),
                service('doctrine'),
            ]);
};
