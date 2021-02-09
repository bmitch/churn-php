<?php

declare(strict_types=1);

namespace Churn\File;

class FileHelper
{

    /**
     * @param string $path The path of an item.
     * @param string $basePath The absolute base path.
     * @return string The absolute path of the given item.
     */
    public static function toAbsolutePath(string $path, string $basePath): string
    {
        $path = \trim($path);

        if (0 === \strpos($path, '/')) {
            return $path;
        }

        if ('\\' === $path[0] || (\strlen($path) >= 3 && \preg_match('#^[A-Z]\:[/\\\]#i', \substr($path, 0, 3)))) {
            return $path;
        }

        if (false !== \strpos($path, '://')) {
            return $path;
        }

        return $basePath . '/' . $path;
    }
}
