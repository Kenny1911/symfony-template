<?php

declare(strict_types=1);

namespace App\User\Credentials;

use Symfony\Component\Uid\Uuid;

/**
 * @api
 */
final readonly class RegisterUser
{
    /**
     * @param non-empty-string $username
     * @param non-empty-string $password
     * @param non-empty-list<User::ROLE_*> $roles
     */
    public function __construct(
        public Uuid $id,
        public string $username,
        #[\SensitiveParameter]
        public string $password,
        public array $roles,
    ) {}
}
