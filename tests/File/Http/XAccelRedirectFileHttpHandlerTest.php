<?php

declare(strict_types=1);

namespace App\Tests\File\Http;

use App\File\Http\XAccelRedirectFileHttpHandler;
use App\File\Shared\FileNotExists;
use App\File\Shared\PathNormalizer;
use App\Tests\File\LocalFileStorageTestTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @psalm-internal App\Tests\File\Http
 */
final class XAccelRedirectFileHttpHandlerTest extends TestCase
{
    use LocalFileStorageTestTrait;

    private XAccelRedirectFileHttpHandler $fileHttpHandler;

    #[\Override]
    protected function setUp(): void
    {
        self::initFileStorage();
        $this->fileHttpHandler = new XAccelRedirectFileHttpHandler(
            fileStorage: $this->fileStorage,
            pathNormalizer: new PathNormalizer(),
            baseXAccelRedirect: '__protected_uploads__',
        );
    }

    /**
     * @throws FileNotExists
     */
    #[Test]
    public function httpResponse(): void
    {
        $path = $this->fileStorage->upload(self::createUploadFile('foo.txt', 'ok'));
        $response = $this->fileHttpHandler->httpResponse($path);

        self::assertSame('/__protected_uploads__/' . $path, $response->headers->get('X-Accel-Redirect'));
    }

    #[Test]
    public function httpResponseThrowFileNotExists(): void
    {
        self::expectException(FileNotExists::class);

        $this->fileHttpHandler->httpResponse('path/not/exists.txt');
    }
}
