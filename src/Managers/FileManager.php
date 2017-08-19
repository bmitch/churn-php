<?php declare(strict_types = 1);

namespace Churn\Managers;

use Churn\Collections\FileCollection;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Churn\Values\File;

class FileManager
{
    /**
     * Recursively finds all files with the .php extension in the provided
     * $path and returns list as array.
     * @param  string $path Path to look for .php files.
     * @return FileCollection
     */
    public function getPhpFiles(string $path): FileCollection
    {
        $directoryIterator = new RecursiveDirectoryIterator($path);
        $files = new FileCollection;
        foreach (new RecursiveIteratorIterator($directoryIterator) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $files->push(new File(['displayPath' => $file->getPathName(), 'fullPath' => $file->getRealPath()]));
        }

        return $files;
    }
}
