<?php

declare(strict_types=1);

namespace App\Gateway;

/**
 * @internal
 * @psalm-internal App\Gateway
 * @psalm-internal App\ItOps\Twig\GlobalVariable
 */
final readonly class RouteName
{
    // Common pages
    public const string HOME = 'home';
    public const string LOGIN = 'login';
    public const string LOGIN_REGEX = '^login$';
    public const string LOGOUT = 'logout';

    // Admin
    public const string ADMIN_PREFIX = 'admin.';
    public const string ADMIN_PREFIX_REGEX = '^admin\.';

    public function __get(string $name): string
    {
        return (string) self::{$name};
    }
}
