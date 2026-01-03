<?php

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\AbstractUid;

/**
 * @internal
 * @psalm-internal App\Tests
 *
 * @psalm-require-extends TestCase
 */
trait AssertionsTrait
{
    final protected static function assertTimestamp(\DateTimeInterface $expected, mixed $actual, string $message = ''): void
    {
        self::assertInstanceOf(\DateTimeInterface::class, $actual);
        self::assertSame($expected->getTimestamp(), $actual->getTimestamp(), $message);
    }

    final protected static function assertUuid(AbstractUid $expected, mixed $actual, string $message = ''): void
    {
        self::assertInstanceOf(AbstractUid::class, $actual);
        self::assertSame($expected->toString(), $actual->toString(), $message);
    }
}
