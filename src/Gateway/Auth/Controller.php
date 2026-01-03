<?php

declare(strict_types=1);

namespace App\Gateway\Auth;

use App\Gateway\RouteName;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @internal
 * @psalm-internal App\Gateway\Auth
 */
final readonly class Controller
{
    #[Route(path: '/login', name: RouteName::LOGIN)]
    public function login(AuthenticationUtils $authUtils): LoginView
    {
        return new LoginView(
            lastAuthenticationError: $authUtils->getLastAuthenticationError(),
            lastUsername: $authUtils->getLastUsername(),
        );
    }
}
