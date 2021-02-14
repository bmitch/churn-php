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
class MercurialChangesCountProcess extends ChurnProcess implements ChangesCountInterface
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
            'hg', '--pager=never', '--color=never',
            'log', '--date', "$dateSince to now",
            $file->getFullPath(), '--template=\'1\\n\'',
        ], \dirname($file->getFullPath()));

        parent::__construct($file, $process);
    }

    /**
     * Returns the number of changes for a file.
     */
    public function countChanges(): int
    {
        return \substr_count($this->getOutput(), "\n");
    }
}
