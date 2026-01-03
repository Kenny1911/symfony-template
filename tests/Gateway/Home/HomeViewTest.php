<?php

declare(strict_types=1);

namespace App\Tests\Gateway\Home;

use App\Gateway\Home\HomeView;
use App\Tests\ViewTestCase;

/**
 * @internal
 * @psalm-internal App\Tests\Gateway\Home
 */
final class HomeViewTest extends ViewTestCase
{
    #[\Override]
    protected function iterateViews(): iterable
    {
        yield new HomeView();
    }
}
