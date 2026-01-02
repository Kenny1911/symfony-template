<?php

declare(strict_types=1);

namespace App\ItOps\Symfony\Security\Voter;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->defaults()
            ->autoconfigure()
        ->set(AnonymousVoter::class);
};
