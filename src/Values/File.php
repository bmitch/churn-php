<?php declare(strict_types = 1);

namespace Churn\Values;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class File
{
    public function __construct($file)
    {
        $this->fullPath = $file->getRealPath();
        $this->displayPath = $file->getPathName();
    }

    public function getFullPath()
    {
        return $this->fullPath;
    }

    public function getDisplayPath()
    {
        return $this->displayPath;
    }

    public function getFolder()
    {
        $pos =strrpos($this->fullPath, '/');
        return substr($this->fullPath, 0, $pos);
    }
}
