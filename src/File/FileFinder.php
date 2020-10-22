<?php declare(strict_types = 1);

namespace Churn\File;

use const DIRECTORY_SEPARATOR;
use Generator;
use function in_array;
use function preg_match;
use function preg_replace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use function str_replace;

class FileFinder
{
    /**
     * List of file extensions to look for.
     * @var array
     */
    private $fileExtensions;

    /**
     * List of files to ignore.
     * @var array
     */
    private $filesToIgnore;

    /**
     * Class constructor.
     * @param array $fileExtensions List of file extensions to look for.
     * @param array $filesToIgnore  List of files to ignore.
     */
    public function __construct(array $fileExtensions, array $filesToIgnore)
    {
        $this->fileExtensions = $fileExtensions;
        $this->filesToIgnore = $filesToIgnore;
    }

    /**
     * Recursively finds all files with the .php extension in the provided
     * $paths and returns list as array.
     * @param array $paths Paths in which to look for .php files.
     * @return Generator
     */
    public function getPhpFiles(array $paths): Generator
    {
        foreach ($paths as $path) {
            yield from $this->getPhpFilesFromPath($path);
        }
    }

    /**
     * Recursively finds all files with the .php extension in the provided
     * $path adds them to $this->files.
     * @param string $path Path in which to look for .php files.
     * @return Generator
     */
    private function getPhpFilesFromPath(string $path): Generator
    {
        foreach ($this->getPathIterator($path) as $file) {
            if (!in_array($file->getExtension(), $this->fileExtensions)
            || $this->fileShouldBeIgnored($file)) {
                continue;
            }

            yield new File($file->getRealPath(), $file->getPathName());
        }
    }

    /**
     * Returns a recursive iterator for a given directory.
     * @param string $path Path in which to look for .php files.
     * @return RecursiveIteratorIterator
     */
    private function getPathIterator(string $path): RecursiveIteratorIterator
    {
        $directoryIterator = new RecursiveDirectoryIterator($path);
        return new RecursiveIteratorIterator($directoryIterator);
    }

    /**
     * Determines if a file should be ignored.
     * @param \SplFileInfo $file File.
     * @return boolean
     */
    private function fileShouldBeIgnored(SplFileInfo $file): bool
    {
        foreach ($this->filesToIgnore as $fileToIgnore) {
            $regex = $this->patternToRegex($fileToIgnore);
            if (preg_match("#{$regex}#", $file->getRealPath())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Translate file path pattern to regex string.
     * @param string $filePattern File pattern to be ignored.
     * @return string
     */
    private function patternToRegex(string $filePattern): string
    {
        $regex = preg_replace("#(.*)\*([\w.]*)$#", "$1.+$2$", $filePattern);
        if (DIRECTORY_SEPARATOR === '\\') {
            $regex = str_replace('/', '\\\\', $regex);
        }

        return $regex;
    }
}
