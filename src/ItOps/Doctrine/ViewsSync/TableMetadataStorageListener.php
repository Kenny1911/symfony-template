<?php

declare(strict_types=1);

namespace App\ItOps\Doctrine\ViewsSync;

use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Kenny1911\DoctrineViewsSync\Metadata\TableMetadataStorage;

/**
 * @internal
 * @psalm-internal App\ItOps\Doctrine\ViewsSync
 */
final readonly class TableMetadataStorageListener
{
    public function __construct(
        private TableMetadataStorage $metadataStorage,
    ) {}

    public function postGenerateSchema(GenerateSchemaEventArgs $event): void
    {
        $this->metadataStorage->configureSchema($event->getSchema());
    }
}
