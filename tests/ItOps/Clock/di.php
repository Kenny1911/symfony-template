<?php

declare(strict_types=1);

namespace App\Tests\ItOps\Clock;

use Symfony\Component\Clock\MockClock;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->set('clock', MockClock::class);
};
