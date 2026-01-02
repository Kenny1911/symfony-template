<?php

declare(strict_types=1);

namespace App\ItOps\Symfony\HttpFoundation\FlashBag;

/**
 * @api
 */
enum Type: string
{
    case INFO = 'info';
    case SUCCESS = 'success';
    case WARNING = 'warning';
    case ERROR = 'error';
}
