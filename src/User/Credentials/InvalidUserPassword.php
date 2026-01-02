<?php

declare(strict_types=1);

namespace App\User\Credentials;

use Kenny1911\SymfonyHttpException\Attribute\HttpException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @api
 */
#[HttpException(Response::HTTP_UNAUTHORIZED, message: 'Invalid credentials.', translationDomain: 'security')]
final class InvalidUserPassword extends \Exception
{
    public static function create(): self
    {
        return new self('Invalid user password.');
    }
}
