<?php

declare(strict_types=1);

namespace App\User\Credentials;

use Symfony\Component\Uid\Uuid;

/**
 * @api
 */
final readonly class ResetUserPassword
{
    /**
     * @param non-empty-string $newPassword
     */
    public function __construct(
        public Uuid $id,
        #[\SensitiveParameter]
        public string $newPassword,
    ) {}
}
