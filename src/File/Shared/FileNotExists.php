<?php

declare(strict_types=1);

namespace App\File\Shared;

/**
 * @api
 */
final class FileNotExists extends \Exception
{
    public static function create(string $path): self
    {
        return new self(\sprintf('File "%s" not exists.', $path));
    }
}
