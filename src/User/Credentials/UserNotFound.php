<?php

declare(strict_types=1);

namespace App\User\Credentials;

/**
 * @internal
 * @psalm-internal App\User\Credentials
 */
final class UserNotFound extends \Exception
{
    public static function create(): self
    {
        return new self('User not found.');
    }
}
