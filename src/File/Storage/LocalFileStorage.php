<?php

declare(strict_types=1);

namespace App\File\Storage;

use App\File\Shared\FileNotExists;
use App\File\Shared\PathNormalizer;
use Psr\Clock\ClockInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @internal
 * @psalm-internal App\File\Storage
 * @psalm-internal App\Tests\File
 */
final readonly class LocalFileStorage implements FileStorage
{
    private string $baseDir;

    public function __construct(
        string $baseDir,
        private PathNormalizer $pathNormalizer,
        private ClockInterface $clock,
    ) {
        $isAbsolute = str_starts_with($baseDir, '/');

        if (!$isAbsolute) {
            throw new \RuntimeException('Base directory path must be absolute.');
        }

        $this->baseDir = '/' . $this->pathNormalizer->normalizePath($baseDir);

        if (!(file_exists($this->baseDir) && is_dir($this->baseDir))) {
            throw new \RuntimeException('Base directory not exists.');
        }
    }

    #[\Override]
    public function upload(UploadedFile $file): string
    {
        if (!$file->isFile()) {
            throw new \RuntimeException('Invalid file.');
        }

        $uploadPath = $this->generateUploadPath($file);
        $absoluteUploadPath = $this->baseDir . '/' . $uploadPath;

        $file->move(\dirname($absoluteUploadPath), basename($absoluteUploadPath));

        return $uploadPath;
    }

    #[\Override]
    public function absolutePath(string $path): string
    {
        $fullPath = $this->baseDir . '/' . $this->pathNormalizer->normalizePath($path);

        if (!(file_exists($fullPath) && is_file($fullPath))) {
            throw FileNotExists::create($path);
        }

        return $fullPath;
    }

    #[\Override]
    public function fileExists(string $path): bool
    {
        $fullPath = $this->baseDir . '/' . $this->pathNormalizer->normalizePath($path);

        return file_exists($fullPath) && is_file($fullPath);
    }

    /**
     * @return non-empty-string
     */
    private function generateUploadPath(UploadedFile $file, string $suffix = ''): string
    {
        $now = $this->clock->now();

        if ('' === $suffix) {
            $filename = $file->getClientOriginalName();
        } else {
            $filename = \sprintf(
                '%s-%s.%s',
                pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                $suffix,
                $file->getClientOriginalExtension(),
            );
        }

        $fullFilename = \sprintf(
            '%s/%s/%s/%s',
            $now->format('Y'),
            $now->format('m'),
            $now->format('d'),
            $filename,
        );

        if (!$this->fileExists($fullFilename)) {
            return $fullFilename;
        }

        return $this->generateUploadPath($file, uniqid());
    }
}
