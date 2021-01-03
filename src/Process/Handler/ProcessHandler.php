<?php

declare(strict_types=1);

namespace Churn\Process\Handler;

use Churn\Process\Observer\OnSuccess;
use Churn\Process\ProcessFactory;
use Generator;

interface ProcessHandler
{

    /**
     * Run the processes to gather information.
     *
     * @param Generator $filesFinder Collection of files.
     * @param ProcessFactory $processFactory Process Factory.
     * @param OnSuccess $onSuccess The OnSuccess event observer.
     */
    public function process(Generator $filesFinder, ProcessFactory $processFactory, OnSuccess $onSuccess): void;
}
