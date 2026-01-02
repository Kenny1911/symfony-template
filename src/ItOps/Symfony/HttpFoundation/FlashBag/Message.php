<?php

declare(strict_types=1);

namespace App\ItOps\Symfony\HttpFoundation\FlashBag;

/**
 * @api
 */
final readonly class Message implements \Stringable
{
    /**
     * @param non-empty-string $text
     */
    public function __construct(
        public Type $type,
        public string $text,
    ) {}

    #[\Override]
    public function __toString(): string
    {
        return $this->text;
    }
}
