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
final class FossilChangesCountProcess extends ChurnProcess implements ChangesCountInterface
{
    /**
     * Class constructor.
     *
     * @param File $file The file the process is being executed on.
     * @param string $dateSince String containing the date of when to look at commits since (Y-m-d).
     */
    public function __construct(File $file, string $dateSince)
    {
        $process = new Process([
            'fossil', 'timeline', '-t', 'ci',
            '-W', '0', 'after', $dateSince,
            '-p', $file->getFullPath(),
        ], \dirname($file->getFullPath()));

        parent::__construct($file, $process);
    }

    /**
     * Returns the number of changes for a file.
     */
    #[\Override]
    public function countChanges(): int
    {
        $count = 0;

        foreach (\explode("\n", $this->getOutput()) as $row) {
            if (!isset($row[0]) || '=' === $row[0] || '+' === $row[0]) {
                continue;
            }

            $count++;
        }

        return $count;
    }
}
