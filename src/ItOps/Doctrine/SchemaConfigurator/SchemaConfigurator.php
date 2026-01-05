<?php

declare(strict_types=1);

namespace App\ItOps\Doctrine\SchemaConfigurator;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;

/**
 * @api
 */
interface SchemaConfigurator
{
    /**
     * @throws SchemaException
     */
    public function configureSchema(Schema $schema): void;
}
