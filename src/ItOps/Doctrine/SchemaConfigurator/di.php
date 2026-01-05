<?php

declare(strict_types=1);

namespace App\ItOps\Doctrine\SchemaConfigurator;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $di, ContainerBuilder $builder): void {
    $builder->addCompilerPass(new SchemaConfiguratorPass('doctrine.schema_configurator', false));

    $di->services()
        ->set(SchemaConfiguratorListener::class)
            ->args([
                tagged_iterator('doctrine.schema_configurator'),
            ])
            ->tag('doctrine.event_listener', ['event' => 'postGenerateSchema']);
};
