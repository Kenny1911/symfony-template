<?php

declare(strict_types=1);

namespace App\ItOps\Doctrine\SchemaConfigurator;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @internal
 * @psalm-internal App\ItOps\Doctrine\SchemaConfigurator
 */
final readonly class SchemaConfiguratorPass implements CompilerPassInterface
{
    public function __construct(
        private string $tag,
        private bool $onlyAutoconfigured,
    ) {}

    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $definition) {
            if ($definition->isAbstract() || $definition->isSynthetic()) {
                continue;
            }

            if ($this->onlyAutoconfigured && !$definition->isAutoconfigured()) {
                continue;
            }

            if ($definition->hasTag($this->tag)) {
                continue;
            }

            $class = $definition->getClass();

            if (\is_string($class) && class_exists($class) && is_subclass_of($class, SchemaConfigurator::class)) {
                $definition->addTag($this->tag);
            }
        }
    }
}
