<?php

declare(strict_types=1);

namespace App\Tests\File;

use App\File\Shared\PathNormalizer;
use App\File\Storage\LocalFileStorage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @internal
 * @psalm-internal App\Tests\File
 *
 * @psalm-require-extends TestCase
 */
trait LocalFileStorageTestTrait
{
    private const string BASE_DIR = __DIR__ . '/files';

    private LocalFileStorage $fileStorage;

    private function initFileStorage(): void
    {
        $fs = new Filesystem();

        if (file_exists(self::BASE_DIR)) {
            $fs->remove(self::BASE_DIR);
        }

        $fs->mkdir(self::BASE_DIR);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->fileStorage = new LocalFileStorage(
            baseDir: self::BASE_DIR,
            pathNormalizer: new PathNormalizer(),
            clock: new MockClock(new \DateTimeImmutable('2026-01-01 00:00:00')),
        );
    }

    private static function createUploadFile(string $originalName, string $content = ''): UploadedFile
    {
        $path = (string) tempnam(sys_get_temp_dir(), 'upload_');
        \assert('' !== $path);
        file_put_contents($path, $content);

        return new UploadedFile(path: $path, originalName: $originalName, test: true);
    }
}
