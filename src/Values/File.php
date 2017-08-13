<?php declare(strict_types = 1);

namespace Churn\Values;

class File
{
    /**
     * The full path of the file.
     * @var string
     */
    private $fullPath;

    /**
     * The display path of the file.
     * @var string
     */
    private $displayPath;

    /**
     * File constructor.
     * @param array $fileData Raw file data.
     */
    public function __construct(array $fileData)
    {
        $this->fullPath = $fileData['fullPath'];
        $this->displayPath = $fileData['displayPath'];
    }

    /**
     * Get the full path of the file.
     * @return string
     */
    public function getFullPath(): string
    {
        return $this->fullPath;
    }

    /**
     * Get the display path of the file.
     * @return string
     */
    public function getDisplayPath(): string
    {
        return $this->displayPath;
    }
}
