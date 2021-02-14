<?php

declare(strict_types=1);

namespace Churn\Process\ChangesCount;

use Churn\File\File;
use Churn\Process\ChangesCountInterface;
use Churn\Process\ChurnProcess;
use Symfony\Component\Process\Process;

/**
 * @internal
 */
class SubversionChangesCountProcess extends ChurnProcess implements ChangesCountInterface
{

    /**
     * Class constructor.
     *
     * @param File $file The file the process is being executed on.
     * @param string $dateRange String containing a date range formatted for SVN.
     */
    public function __construct(File $file, string $dateRange)
    {
        $process = new Process(['svn', 'log', '-q', '-r', $dateRange, $file->getFullPath()]);

        parent::__construct($file, $process);
    }

    /**
     * Returns the number of changes for a file.
     */
    public function countChanges(): int
    {
        return (int) \floor(\substr_count($this->getOutput(), "\n") / 2);
    }
}
