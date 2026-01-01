<?php

declare(strict_types=1);

namespace App\ItOps\Symfony\Form\AttributeForm;

use Kenny1911\SymfonyAttributeForm\AttributeFormFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->set(AttributeFormFactory::class)
            ->args([
                service('form.factory'),
            ]);
};
