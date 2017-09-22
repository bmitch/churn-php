<?php declare(strict_types = 1);

namespace Churn\Managers;

use Churn\Collections\FileCollection;
use Churn\Values\Config;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Churn\Values\File;
use SplFileInfo;

class FileManager
{
    /**
     * The config values.
     * @var Config
     */
    private $config;

    /**
     * Collection of File objects.
     * @var FileCollection;
     */
    private $files;

    /**
     * FileManager constructor.
     * @param Config $config Configuration Settings.
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Recursively finds all files with the .php extension in the provided
     * $paths and returns list as array.
     * @param  array $paths Paths in which to look for .php files.
     * @return FileCollection
     */
    public function getPhpFiles(array $paths): FileCollection
    {
        $this->files = new FileCollection;
        foreach ($paths as $path) {
            $this->getPhpFilesFromPath($path);
        }

        return $this->files;
    }

    /**
     * Recursively finds all files with the .php extension in the provided
     * $path adds them to $this->files.
     * @param  string $path Path in which to look for .php files.
     * @return void
     */
    private function getPhpFilesFromPath(string $path)
    {
        $directoryIterator = new RecursiveDirectoryIterator($path);
        foreach (new RecursiveIteratorIterator($directoryIterator) as $file) {
            if (!in_array($file->getExtension(), $this->config->getFileExtensions())) {
                continue;
            }

            if ($this->fileShouldBeIgnored($file)) {
                continue;
            }

            $this->files->push(new File(['displayPath' => $file->getPathName(), 'fullPath' => $file->getRealPath()]));
        }
    }

    /**
     * Determines if a file should be ignored.
     * @param \SplFileInfo $file File.
     * @return boolean
     */
    private function fileShouldBeIgnored(SplFileInfo $file): bool
    {
        foreach ($this->config->getFilesToIgnore() as $fileToIgnore) {
            $fileToIgnore = str_replace('/', '\/', $fileToIgnore);
            if (preg_match("/{$fileToIgnore}/", $file->getPathName())) {
                return true;
            }
        }

        return false;
    }
}
