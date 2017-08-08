<?php declare(strict_types = 1);

namespace Churn\Managers;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Churn\Values\File;

class FileManager
{
    /**
     * Recursively finds all files with the .php extension in the provided
     * $path and returns list as array.
     * @param  string $path Path to look for .php files.
     * @return array
     */
    public function getPhpFiles(string $path): array
    {
        $directoryIterator = new RecursiveDirectoryIterator($path);
        $files = [];
        foreach (new RecursiveIteratorIterator($directoryIterator) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $files[] = new File($file);
        }

        return $files;
    }
}
