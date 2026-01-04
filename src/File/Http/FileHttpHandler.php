<?php

declare(strict_types=1);

namespace App\File\Http;

use App\File\Shared\FileNotExists;
use Symfony\Component\HttpFoundation\Response;

/**
 * @api
 */
interface FileHttpHandler
{
    /**
     * @param non-empty-string $path
     *
     * @throws FileNotExists
     */
    public function httpResponse(string $path): Response;
}
