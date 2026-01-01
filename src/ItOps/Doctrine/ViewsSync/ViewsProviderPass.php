<?php

declare(strict_types=1);

namespace App\ItOps\Doctrine\ViewsSync;

use Kenny1911\DoctrineViewsSync\ViewsProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @internal
 * @psalm-internal App\ItOps\Doctrine\ViewsSync
 */
final readonly class ViewsProviderPass implements CompilerPassInterface
{
    public function __construct(
        private string $viewsProviderId,
        private bool $onlyAutoconfigured,
    ) {}

    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $id => $definition) {
            // Skip root ViewsProvider service
            if ($this->viewsProviderId === $id) {
                continue;
            }

            // Skip abstract and synthetic services
            if ($definition->isAbstract() || $definition->isSynthetic()) {
                continue;
            }

            // Skip, if service already has tag 'doctrine.views_provider'
            if ($definition->hasTag('doctrine.views_provider')) {
                continue;
            }

            // Skip, if supports only autoconfigured services, but service not autoconfigured
            if ($this->onlyAutoconfigured && !$definition->isAutoconfigured()) {
                continue;
            }

            $class = $definition->getClass();

            if (\is_string($class) && class_exists($class) && is_subclass_of($class, ViewsProvider::class)) {
                $definition->addTag('doctrine.views_provider');
            }
        }
    }
}
