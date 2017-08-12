<?php declare(strict_types = 1);

namespace Churn\Managers;

use Illuminate\Support\Collection;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Churn\Values\File;

class FileManager
{
    /**
     * Recursively finds all files with the .php extension in the provided
     * $path and returns list as array.
     * @param  string $path Path to look for .php files.
     * @return Collection
     */
    public function getPhpFiles(string $path): Collection
    {
        $directoryIterator = new RecursiveDirectoryIterator($path);
        $files = new Collection;
        foreach (new RecursiveIteratorIterator($directoryIterator) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $files->push(new File(['displayPath' => $file->getPathName(), 'fullPath' => $file->getRealPath()]));
        }

        return $files;
    }
}
