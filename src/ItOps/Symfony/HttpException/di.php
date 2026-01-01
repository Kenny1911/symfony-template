<?php

declare(strict_types=1);

namespace App\ItOps\Symfony\HttpException;

use Kenny1911\SymfonyHttpException\ErrorListener;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use function Symfony\Component\DependencyInjection\Loader\Configurator\inline_service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $di, ContainerBuilder $builder): void {
    $di->services()
        ->set(ErrorListener::class)
            ->args([
                service('translator'),
                class_exists(ExpressionLanguage::class) ? inline_service(ExpressionLanguage::class) : null,
            ])
            ->tag('kernel.event_subscriber');
};
