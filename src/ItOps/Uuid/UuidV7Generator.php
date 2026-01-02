<?php

declare(strict_types=1);

namespace App\ItOps\Uuid;

use Psr\Clock\ClockInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

/**
 * @internal
 * @psalm-internal App\ItOps\Uuid
 */
final readonly class UuidV7Generator implements UuidGenerator
{
    public function __construct(
        private ?ClockInterface $clock = null,
    ) {}

    public function generate(): Uuid
    {
        return new UuidV7(
            UuidV7::generate($this->clock?->now()),
        );
    }
}
