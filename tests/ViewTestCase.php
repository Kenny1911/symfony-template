<?php

declare(strict_types=1);

namespace App\Tests;

use Kenny1911\TwigView\Test\ViewTest;
use Kenny1911\TwigView\ViewRenderer;

/**
 * @internal
 * @psalm-internal App\Tests
 */
abstract class ViewTestCase extends KernelTestCase
{
    use ViewTest;

    #[\Override]
    protected function createViewRenderer(): ViewRenderer
    {
        self::bootKernel();
        $viewRenderer = self::getContainer()->get('twig.view_renderer');
        \assert($viewRenderer instanceof ViewRenderer);

        return $viewRenderer;
    }
}
