<?php

declare(strict_types=1);

namespace App\File\Http;

use App\File\Storage\FileStorage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @psalm-internal App\File\Http
 * @psalm-internal App\Tests\File\Http
 */
final readonly class BinaryFileHttpHandler implements FileHttpHandler
{
    public function __construct(
        private FileStorage $fileStorage,
    ) {}

    #[\Override]
    public function httpResponse(string $path): Response
    {
        $absolutePath = $this->fileStorage->absolutePath($path);

        return new BinaryFileResponse($absolutePath);
    }
}
