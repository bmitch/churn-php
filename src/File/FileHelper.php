<?php

declare(strict_types=1);

namespace Churn\File;

use InvalidArgumentException;
use Symfony\Component\Filesystem\Exception\InvalidArgumentException as FilesystemException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @internal
 */
class FileHelper
{
    /**
     * @param string $path The path of an item.
     * @param string $basePath The absolute base path.
     * @return string The absolute path of the given item.
     */
    public static function toAbsolutePath(string $path, string $basePath): string
    {
        return (new Filesystem())->isAbsolutePath($path)
            ? $path
            : $basePath . '/' . $path;
    }

    /**
     * @param string $path The absolute path of an item.
     * @param string $basePath The absolute base path.
     * @return string The relative path of the given item.
     */
    public static function toRelativePath(string $path, string $basePath): string
    {
        try {
            $relativePath = (new Filesystem())->makePathRelative($path, $basePath);

            return \rtrim($relativePath, '/\\');
        } catch (FilesystemException $e) {
            return $path;
        }
    }

    /**
     * Check whether the path is writable and create the missing folders if needed.
     *
     * @param string $filePath The file path to check.
     * @throws InvalidArgumentException If the path is invalid.
     */
    public static function ensureFileIsWritable(string $filePath): void
    {
        if ('' === $filePath) {
            throw new InvalidArgumentException('Path cannot be empty');
        }

        if (\is_dir($filePath)) {
            throw new InvalidArgumentException('Path cannot be a folder');
        }

        if (!\is_file($filePath)) {
            (new Filesystem())->mkdir(\dirname($filePath));

            return;
        }

        if (!\is_writable($filePath)) {
            throw new InvalidArgumentException('File is not writable');
        }
    }
}
