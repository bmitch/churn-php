<?php

declare(strict_types=1);

namespace Churn\Process;

use Churn\File\File;

interface ProcessFactory
{

    /**
     * @param File $file File that the processes will execute on.
     * @return iterable<ProcessInterface> The list of processes to execute.
     */
    public function createProcesses(File $file): iterable;
}
