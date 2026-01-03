<?php

declare(strict_types=1);

namespace App\Tests\Gateway\Auth;

use App\Gateway\Auth\LoginView;
use App\Tests\ViewTestCase;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * @internal
 * @psalm-internal App\Tests\Gateway\Auth
 */
final class LoginViewTest extends ViewTestCase
{
    #[\Override]
    protected function iterateViews(): iterable
    {
        yield new LoginView(
            lastAuthenticationError: new AuthenticationException(),
            lastUsername: 'user',
        );

        yield new LoginView(
            lastAuthenticationError: null,
            lastUsername: '',
        );
    }
}
