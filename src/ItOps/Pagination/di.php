<?php

declare(strict_types=1);

namespace App\ItOps\Pagination;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\RequestStack;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->set(Paginator::class)
            ->args([
                service(PaginatorInterface::class),
                service(RequestStack::class),
            ]);
};
