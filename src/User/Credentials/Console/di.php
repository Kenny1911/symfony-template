<?php

declare(strict_types=1);

namespace App\User\Credentials\Console;

use App\ItOps\Uuid\UuidGenerator;
use App\User\Credentials\UserCredentialsManager;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->set(RegisterUserCommand::class)
            ->args([
                service(UserCredentialsManager::class),
                service(UuidGenerator::class),
            ])
            ->autoconfigure();
};
