<?php

declare(strict_types=1);

namespace App\Gateway\Auth;

use Kenny1911\TwigView\View;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * @internal
 * @psalm-internal App\Gateway\Auth
 * @psalm-internal App\Tests\Gateway\Auth
 *
 * @implements View<array{
 *     lastAuthenticationError: AuthenticationException|null,
 *     lastUsername: string
 * }>
 */
final readonly class LoginView implements View
{
    public function __construct(
        private ?AuthenticationException $lastAuthenticationError,
        private string $lastUsername,
    ) {}

    #[\Override]
    public function template(): string
    {
        return 'page/auth/login.html.twig';
    }

    #[\Override]
    public function context(): array
    {
        return [
            'lastAuthenticationError' => $this->lastAuthenticationError,
            'lastUsername' => $this->lastUsername,
        ];
    }
}
