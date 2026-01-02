<?php

declare(strict_types=1);

namespace App\ItOps\Pagination;

use Knp\Component\Pager\Event\Subscriber\Paginate\Callback\CallbackPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Facade of {@see PaginatorInterface} for simplify pagination.
 *
 * @api
 */
final readonly class Paginator
{
    public const int DEFAULT_LIMIT = 10;

    public function __construct(
        private PaginatorInterface $paginator,
        private RequestStack $requestStack,
    ) {}

    /**
     * @template T
     *
     * @param callable(): non-negative-int $count
     * @param callable(non-negative-int, non-negative-int): iterable<T> $items Callback, that takes 2 arguments: offset and limit items on page
     * @param non-empty-string $pageName
     * @param non-empty-string $limitName
     * @param positive-int $limit
     *
     * @return PaginationInterface<array-key, T>
     */
    public function paginate(
        callable $count,
        callable $items,
        string $pageName = 'page',
        string $limitName = 'limit',
        int $limit = self::DEFAULT_LIMIT,
    ): PaginationInterface {
        $request = $this->requestStack->getMainRequest();

        if (null === $request) {
            throw new \LogicException('Can not paginate, if not request instance.');
        }

        $page = max($request->query->getInt($pageName, 1), 1);
        $limit = max($request->query->getInt($limitName, $limit), 1);

        /** @var PaginationInterface<array-key, T> */
        return $this->paginator->paginate(
            target: new CallbackPagination(
                count: $count,
                items: $items,
            ),
            page: $page,
            limit: $limit,
            options: [
                PaginatorInterface::PAGE_PARAMETER_NAME => $pageName,
            ],
        );
    }
}
