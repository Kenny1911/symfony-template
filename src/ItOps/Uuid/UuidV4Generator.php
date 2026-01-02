<?php

declare(strict_types=1);

namespace App\ItOps\Uuid;

use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @psalm-internal App\ItOps\Uuid
 */
final readonly class UuidV4Generator implements UuidGenerator
{
    #[\Override]
    public function generate(): Uuid
    {
        return Uuid::v4();
    }
}
