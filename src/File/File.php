<?php

declare(strict_types=1);

namespace Churn\File;

/**
 * @internal
 */
class File
{

    /**
     * The full path of the file.
     *
     * @var string
     */
    private $fullPath;

    /**
     * The display path of the file.
     *
     * @var string
     */
    private $displayPath;

    /**
     * File constructor.
     *
     * @param string $fullPath The full path of the file.
     * @param string $displayPath The display path of the file.
     */
    public function __construct(string $fullPath, string $displayPath)
    {
        $this->fullPath = $fullPath;
        $this->displayPath = $displayPath;
    }

    /**
     * Get the full path of the file.
     */
    public function getFullPath(): string
    {
        return $this->fullPath;
    }

    /**
     * Get the display path of the file.
     */
    public function getDisplayPath(): string
    {
        return $this->displayPath;
    }
}
