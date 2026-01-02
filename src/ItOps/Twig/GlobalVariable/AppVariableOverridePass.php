<?php

declare(strict_types=1);

namespace App\ItOps\Twig\GlobalVariable;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @internal
 * @psalm-internal App\ItOps\Twig\GlobalVariable
 */
final readonly class AppVariableOverridePass implements CompilerPassInterface
{
    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        // Skip, if no `twig.app_variable` service
        if (!$container->hasDefinition('twig.app_variable')) {
            return;
        }

        $definition = $container->getDefinition('twig.app_variable');
        $definition->setClass(AppVariable::class);
    }
}
