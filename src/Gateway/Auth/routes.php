<?php

declare(strict_types=1);

namespace App\Gateway\Auth;

use App\Gateway\RouteName;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    $routes->import(__DIR__ . '/**/*Controller.php', 'attribute');
    $routes->add(RouteName::LOGOUT, '/logout');
};
