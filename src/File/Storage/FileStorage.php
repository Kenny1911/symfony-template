<?php

declare(strict_types=1);

namespace App\File\Storage;

use App\File\Shared\FileNotExists;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @api
 */
interface FileStorage
{
    /**
     * @return non-empty-string
     */
    public function upload(UploadedFile $file): string;

    /**
     * @param non-empty-string $path
     *
     * @return non-empty-string
     *
     * @throws FileNotExists
     */
    public function absolutePath(string $path): string;

    /**
     * @param non-empty-string $path
     */
    public function fileExists(string $path): bool;
}
