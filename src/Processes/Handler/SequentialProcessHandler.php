<?php declare(strict_types = 1);

namespace Churn\Processes\Handler;

use Churn\Collections\FileCollection;
use Churn\Factories\ProcessFactory;
use Illuminate\Support\Collection;

class SequentialProcessHandler implements ProcessHandler
{
    /**
     * Run the processes sequentially to gather information.
     * @param FileCollection $filesCollection Collection of files.
     * @param ProcessFactory $processFactory  Process Factory.
     * @return Collection
     */
    public function process(
        FileCollection $filesCollection,
        ProcessFactory $processFactory
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
        }
        return new Collection($completedProcessesArray);
    }
}
