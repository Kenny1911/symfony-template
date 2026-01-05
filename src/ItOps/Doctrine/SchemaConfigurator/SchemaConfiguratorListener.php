<?php

declare(strict_types=1);

namespace App\ItOps\Doctrine\SchemaConfigurator;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

/**
 * @internal
 * @psalm-internal App\ItOps\Doctrine\SchemaConfigurator
 */
final readonly class SchemaConfiguratorListener
{
    /**
     * @param iterable<SchemaConfigurator> $schemaConfigurators
     */
    public function __construct(
        private iterable $schemaConfigurators,
    ) {}

    /**
     * @throws SchemaException
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $event): void
    {
        $schema = $event->getSchema();

        foreach ($this->schemaConfigurators as $schemaConfigurator) {
            $schemaConfigurator->configureSchema($schema);
        }
    }
}
