<?php

declare(strict_types=1);

namespace App\ItOps\Breadcrumbs;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @api
 */
final class BreadcrumbsBuilder
{
    /** @var \WeakMap<Request, list<Item>> */
    private \WeakMap $breadcrumbs;

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
        /** @var \WeakMap<Request, list<Item>> */
        $this->breadcrumbs = new \WeakMap();
    }

    public function reset(): self
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return $this;
        }

        unset($this->breadcrumbs[$request]);

        return $this;
    }

    public function addLink(string $title, string $route, array $params = []): self
    {
        $this->addItem(new Item(title: $title, url: $this->urlGenerator->generate($route, $params, UrlGeneratorInterface::ABSOLUTE_URL)));

        return $this;
    }

    public function addTitle(string $title): self
    {
        $this->addItem(new Item(title: $title, url: null));

        return $this;
    }

    public function addItem(Item $item): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return;
        }

        $items = $this->breadcrumbs[$request] ?? [];
        $items[] = $item;
        $this->breadcrumbs[$request] = $items;
    }

    /**
     * @return list<Item>
     */
    public function getItems(): array
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return [];
        }

        /** @var list<Item> */
        return $this->breadcrumbs[$request] ?? [];
    }
}
