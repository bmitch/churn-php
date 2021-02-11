<?php

declare(strict_types=1);

namespace Churn\Process;

use Churn\File\File;

class PredefinedProcess implements ChangesCountInterface, CyclomaticComplexityInterface
{

    /**
     * @var File
     */
    private $file;

    /**
     * @var integer
     */
    private $countChanges;

    /**
     * @var integer
     */
    private $cyclomaticComplexity;

    /**
     * @param File $file The file the process is being executed on.
     * @param integer $countChanges The number of changes of the file.
     * @param integer $cyclomaticComplexity The complexity of the file.
     */
    public function __construct(File $file, int $countChanges, int $cyclomaticComplexity)
    {
        $this->file = $file;
        $this->countChanges = $countChanges;
        $this->cyclomaticComplexity = $cyclomaticComplexity;
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

    /**
     * Returns the number of changes for a file.
     */
    public function countChanges(): int
    {
        return $this->countChanges;
    }

    /**
     * Returns the cyclomatic complexity of a file.
     */
    public function getCyclomaticComplexity(): int
    {
        return $this->cyclomaticComplexity;
    }
}
