<?php declare(strict_types = 1);

namespace Churn\Process\Handler;

use Churn\Collections\FileCollection;
use Churn\Process\Observer\OnSuccess;
use Churn\Process\ProcessFactory;
use Illuminate\Support\Collection;

class SequentialProcessHandler implements ProcessHandler
{
    /**
     * Run the processes sequentially to gather information.
     * @param FileCollection $filesCollection Collection of files.
     * @param ProcessFactory $processFactory  Process Factory.
     * @param OnSuccess      $onSuccess       The OnSuccess event observer.
     * @return Collection
     */
    public function process(
        FileCollection $filesCollection,
        ProcessFactory $processFactory,
        OnSuccess $onSuccess
    ): Collection {
        $completedProcessesArray = [];
        while ($filesCollection->hasFiles()) {
            $file = $filesCollection->getNextFile();
            $process = $processFactory->createGitCommitProcess($file);
            $process->start();
            while (!$process->isSuccessful());
            $completedProcessesArray[$process->getFileName()][$process->getType()] = $process;
            $process = $processFactory->createCyclomaticComplexityProcess($file);
            $process->start();
            while (!$process->isSuccessful());
            $completedProcessesArray[$process->getFileName()][$process->getType()] = $process;
            $onSuccess($file);
        }
        return new Collection($completedProcessesArray);
    }
}
