<?php

declare(strict_types=1);

namespace App\File\Shared;

/**
 * @internal
 * @psalm-internal App\File
 * @psalm-internal App\Tests\File
 */
final readonly class PathNormalizer
{
    public function normalizePath(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $this->rejectFunkyWhiteSpace($path);

        return $this->normalizeRelativePath($path);
    }

    private function rejectFunkyWhiteSpace(string $path): void
    {
        if (preg_match('#\p{C}+#u', $path)) {
            throw new \RuntimeException('Corrupted path detected: ' . $path);
        }
    }

    private function normalizeRelativePath(string $path): string
    {
        $parts = [];

        foreach (explode('/', $path) as $part) {
            switch ($part) {
                case '':
                case '.':
                    break;

                case '..':
                    if (0 === \count($parts)) {
                        throw new \RuntimeException("Path traversal detected: {$path}");
                    }
                    array_pop($parts);
                    break;

                default:
                    $parts[] = $part;
                    break;
            }
        }

        return implode('/', $parts);
    }
}
