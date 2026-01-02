<?php

declare(strict_types=1);

namespace App\ItOps\Symfony\Form\FormNormalizer;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->set(FormNormalizer::class);
};
