<?php

declare(strict_types=1);

namespace App\ItOps\Doctrine\ViewsSync;

use Kenny1911\DoctrineViewsSync\Console\ViewsDropCommand;
use Kenny1911\DoctrineViewsSync\Console\ViewsSyncCommand;
use Kenny1911\DoctrineViewsSync\Metadata\TableMetadataStorage;
use Kenny1911\DoctrineViewsSync\ViewsProvider\ChainViewsProvider;
use Kenny1911\DoctrineViewsSync\ViewsSyncFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $di, ContainerBuilder $builder): void {
    $builder->addCompilerPass(new ViewsProviderPass('doctrine.views_provider', false));

    $di->services()
        ->set('doctrine.views_provider', ChainViewsProvider::class)
            ->args([
                tagged_iterator('doctrine.views_provider'),
            ])
        ->set('doctrine.views_sync.metadata_storage', TableMetadataStorage::class)
            ->args([
                service('doctrine.dbal.default_connection'),
            ])
        ->set(TableMetadataStorageListener::class)
            ->args([
                service('doctrine.views_sync.metadata_storage')
            ])
            ->tag('doctrine.event_listener', ['event' => 'postGenerateSchema'])

        ->set('doctrine.views_sync.factory', ViewsSyncFactory::class)
            ->factory([null, 'fromSingleConnection'])
            ->args([
                service('doctrine.dbal.default_connection'),
                service('doctrine.views_provider'),
                service('doctrine.views_sync.metadata_storage'),
            ])

        // Cli Commands
        ->set(ViewsDropCommand::class)
            ->args([
                service('doctrine.views_sync.factory'),
            ])
            ->autoconfigure()
        ->set(ViewsSyncCommand::class)
            ->args([
                service('doctrine.views_sync.factory'),
            ])
            ->autoconfigure();
};
