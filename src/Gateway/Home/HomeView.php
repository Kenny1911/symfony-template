<?php

declare(strict_types=1);

namespace App\Gateway\Home;

use Kenny1911\TwigView\View;

/**
 * @internal
 * @psalm-internal App\Gateway\Home
 * @psalm-internal App\Tests\Gateway\Home
 *
 * @implements View<array{}>
 */
final readonly class HomeView implements View
{
    #[\Override]
    public function template(): string
    {
        return 'page/home/home.html.twig';
    }

    #[\Override]
    public function context(): array
    {
        return [];
    }
}
