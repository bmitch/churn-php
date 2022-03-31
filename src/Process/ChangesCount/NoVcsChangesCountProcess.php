<?php

declare(strict_types=1);

namespace Churn\Process\ChangesCount;

use Churn\File\File;
use Churn\Process\ChangesCountInterface;

/**
 * @internal
 */
final class NoVcsChangesCountProcess implements ChangesCountInterface
{
    /**
     * The file the process will be executed on.
     *
     * @var File
     */
    private $file;

    /**
     * Class constructor.
     *
     * @param File $file The file the process is being executed on.
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Returns the number of changes for a file.
     */
    public function countChanges(): int
    {
        return 1;
    }

    /**
     * Start the process.
     */
    public function start(): void
    {
        // nothing to do
    }

    /**
     * Determines if the process was successful.
     */
    public function isSuccessful(): bool
    {
        return true;
    }

    /**
     * Gets the file the process is being executed on.
     */
    public function getFile(): File
    {
        return $this->file;
    }
}
