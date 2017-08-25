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
     * FileManager constructor.
     * @param Config $config Configuration Settings.
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

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

            if ($this->fileShouldBeIgnored($file)) {
                continue;
            }


            $files->push(new File(['displayPath' => $file->getPathName(), 'fullPath' => $file->getRealPath()]));
        }

        return $files;
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
