<?php

declare(strict_types=1);

namespace App\ItOps\Breadcrumbs;

/**
 * @api
 */
final readonly class Item
{
    public function __construct(
        public string $title,
        public ?string $url,
    ) {}
}
