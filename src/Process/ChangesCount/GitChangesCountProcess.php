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
final class GitChangesCountProcess extends ChurnProcess implements ChangesCountInterface
{
    /**
     * Class constructor.
     *
     * @param File $file The file the process is being executed on.
     * @param string $commitsSince String containing the date of when to look at commits since.
     */
    public function __construct(File $file, string $commitsSince)
    {
        $process = new Process([
            'git', '-C', \dirname($file->getFullPath()), 'rev-list', '--since',
            $commitsSince, '--no-merges', '--count', 'HEAD',
            $file->getFullPath(),
        ]);

        parent::__construct($file, $process);
    }

    /**
     * Returns the number of changes for a file.
     */
    public function countChanges(): int
    {
        return (int) $this->getOutput();
    }
}
