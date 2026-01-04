<?php

declare(strict_types=1);

namespace App\Tests\File\Http;

use App\File\Http\BinaryFileHttpHandler;
use App\File\Shared\FileNotExists;
use App\Tests\File\LocalFileStorageTestTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * @internal
 * @psalm-internal App\Tests\File\Http
 */
final class BinaryFileHttpHandlerTest extends TestCase
{
    use LocalFileStorageTestTrait;

    private BinaryFileHttpHandler $fileHttpHandler;

    #[\Override]
    protected function setUp(): void
    {
        self::initFileStorage();
        $this->fileHttpHandler = new BinaryFileHttpHandler(fileStorage: $this->fileStorage);
    }

    /**
     * @throws FileNotExists
     */
    #[Test]
    public function httpResponse(): void
    {
        $path = $this->fileStorage->upload(self::createUploadFile('foo.txt', 'ok'));
        $response = $this->fileHttpHandler->httpResponse($path);

        self::assertInstanceOf(BinaryFileResponse::class, $response);
        self::assertSame('ok', $response->getFile()->getContent());
    }

    #[Test]
    public function httpResponseThrowFileNotExists(): void
    {
        self::expectException(FileNotExists::class);

        $this->fileHttpHandler->httpResponse('path/not/exists.txt');
    }
}
