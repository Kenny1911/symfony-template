<?php

declare(strict_types=1);

namespace App\Gateway\Home;

use App\Gateway\RouteName;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @internal
 * @psalm-internal App\Gateway\Home
 */
final readonly class Controller
{
    #[Route(name: RouteName::HOME)]
    public function home(): HomeView
    {
        return new HomeView();
    }
}
