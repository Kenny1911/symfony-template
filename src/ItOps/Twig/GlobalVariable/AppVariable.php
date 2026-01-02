<?php

declare(strict_types=1);

namespace App\ItOps\Twig\GlobalVariable;

use App\Gateway\RouteName;

/**
 * @internal
 * @psalm-internal App\ItOps\Twig\GlobalVariable
 */
final class AppVariable extends \Symfony\Bridge\Twig\AppVariable
{
    public readonly RouteName $route;

    public function __construct()
    {
        $this->route = new RouteName();
    }
}
