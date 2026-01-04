<?php

declare(strict_types=1);

namespace App\File\Http;

use App\File\Shared\PathNormalizer;
use App\File\Storage\FileStorage;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @psalm-internal App\File\Http
 * @psalm-internal App\Tests\File\Http
 */
final readonly class XAccelRedirectFileHttpHandler implements FileHttpHandler
{
    private string $baseXAccelRedirect;

    /**
     * @param non-empty-string $baseXAccelRedirect
     */
    public function __construct(
        private FileStorage $fileStorage,
        private PathNormalizer $pathNormalizer,
        string $baseXAccelRedirect,
    ) {
        $this->baseXAccelRedirect = '/' . $this->pathNormalizer->normalizePath($baseXAccelRedirect) . '/';
    }

    #[\Override]
    public function httpResponse(string $path): Response
    {
        $absolutePath = $this->fileStorage->absolutePath($path);
        $file = new File($absolutePath);
        $mimeType = ((string) $file->getMimeType()) ?: 'application/octet-stream';

        $path = $this->pathNormalizer->normalizePath($path);

        return new Response(headers: [
            'X-Accel-Redirect' => $this->baseXAccelRedirect . $path,
            'Content-Type' => $mimeType,
        ]);
    }
}
