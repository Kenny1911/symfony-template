<?php

declare(strict_types=1);

namespace App\ItOps\Uuid;

use Symfony\Component\Uid\Uuid;

/**
 * @api
 */
interface UuidGenerator
{
    public function generate(): Uuid;
}
