<?php declare(strict_types = 1);

namespace Churn\Process\ChangesCount;

use Churn\File\File;
use Churn\Process\ChangesCountInterface;

class NoVcsChangesCountProcess implements ChangesCountInterface
{
    /**
     * The file the process will be executed on.
     * @var File
     */
    private $file;

    /**
     * Class constructor.
     * @param File $file The file the process is being executed on.
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Returns the number of changes for a file.
     * @return integer
     */
    public function countChanges(): int
    {
        return 1;
    }

    /**
     * Start the process.
     * @return void
     */
    public function start(): void
    {
    }

    /**
     * Determines if the process was successful.
     * @return boolean
     */
    public function isSuccessful(): bool
    {
        return true;
    }

    /**
     * Gets the file name of the file the process
     * is being executed on.
     * @return string
     */
    public function getFilename(): string
    {
        return $this->file->getDisplayPath();
    }

    /**
     * Gets the file the process is being executed on.
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }
}
