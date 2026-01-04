<?php

declare(strict_types=1);

namespace App\Gateway\Uploads;

use App\File\Http\FileHttpHandler;
use App\File\Shared\FileNotExists;
use App\Gateway\RouteName;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @internal
 * @psalm-internal App\Gateway\Uploads
 */
final readonly class Controller
{
    public function __construct(
        private FileHttpHandler $fileHttpHandler,
    ) {}

    /**
     * @param non-empty-string $path
     */
    #[Route(path: '/uploads/{path}', name: RouteName::UPLOADS_FILE, requirements: ['path' => '.+'])]
    public function __invoke(string $path): Response
    {
        try {
            return $this->fileHttpHandler->httpResponse($path);
        } catch (FileNotExists $e) {
            throw new NotFoundHttpException('Not found', $e);
        }
    }
}
