<?php

declare(strict_types=1);

namespace App\Tests\File\Storage;

use App\File\Shared\FileNotExists;
use App\Tests\File\LocalFileStorageTestTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @psalm-internal App\Tests\File\Storage
 */
final class LocalFileStorageTest extends TestCase
{
    use LocalFileStorageTestTrait;

    #[\Override]
    protected function setUp(): void
    {
        self::initFileStorage();
    }

    /**
     * @throws FileNotExists
     */
    #[Test]
    public function upload(): void
    {
        $file = self::createUploadFile('foo.txt', 'ok');

        $path = $this->fileStorage->upload($file);

        self::assertSame(self::BASE_DIR . '/' . $path, $this->fileStorage->absolutePath($path));
        self::assertTrue($this->fileStorage->fileExists($path));
        self::assertSame('ok', file_get_contents($this->fileStorage->absolutePath($path)));
    }

    /**
     * @throws FileNotExists
     */
    #[Test]
    public function uploadAgain(): void
    {
        $path1 = $this->fileStorage->upload(self::createUploadFile('foo.txt', '1'));

        self::assertSame('2026/01/01/foo.txt', $path1);
        self::assertSame('1', file_get_contents($this->fileStorage->absolutePath($path1)));

        $path2 = $this->fileStorage->upload(self::createUploadFile('foo.txt', '2'));

        self::assertMatchesRegularExpression('/^2026\/01\/01\/foo-[0-9a-f]{13}.txt$/', $path2);
        self::assertSame('2', file_get_contents($this->fileStorage->absolutePath($path2)));
    }

    #[Test]
    public function absolutePathThrowFileNotExists(): void
    {
        self::expectException(FileNotExists::class);

        $this->fileStorage->absolutePath('path/not/exists.txt');
    }

    #[Test]
    public function fileExistsNotExists(): void
    {
        self::assertFalse($this->fileStorage->fileExists('path/not/exists.txt'));
    }
}
