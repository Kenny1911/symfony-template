<?php

declare(strict_types=1);

namespace App\Tests;

use Kenny1911\TwigView\Renderer;
use Kenny1911\TwigView\Test\ViewTest;

/**
 * @internal
 * @psalm-internal App\Tests
 */
abstract class ViewTestCase extends KernelTestCase
{
    use ViewTest;

    #[\Override]
    protected function createViewRenderer(): Renderer
    {
        self::bootKernel();
        $viewRenderer = self::getContainer()->get('twig.view_renderer');
        \assert($viewRenderer instanceof Renderer);

        return $viewRenderer;
    }
}
