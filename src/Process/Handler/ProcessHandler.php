<?php declare(strict_types = 1);

namespace Churn\Process\Handler;

use Churn\Collections\FileCollection;
use Churn\Process\Observer\OnSuccess;
use Churn\Process\ProcessFactory;

interface ProcessHandler
{
    /**
     * Run the processes to gather information.
     * @param FileCollection $filesCollection Collection of files.
     * @param ProcessFactory $processFactory  Process Factory.
     * @param OnSuccess      $onSuccess       The OnSuccess event observer.
     * @return void
     */
    public function process(
        FileCollection $filesCollection,
        ProcessFactory $processFactory,
        OnSuccess $onSuccess
    ): void;
}
