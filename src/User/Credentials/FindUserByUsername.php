<?php

declare(strict_types=1);

namespace App\User\Credentials;

/**
 * @api
 */
final readonly class FindUserByUsername
{
    /**
     * @param non-empty-string $username
     */
    public function __construct(
        public string $username,
    ) {}
}
