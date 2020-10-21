<?php declare(strict_types = 1);

namespace Churn\Process\Handler;

use Churn\Collections\FileCollection;
use Churn\Process\Observer\OnSuccess;
use Churn\Process\ProcessFactory;
use Churn\Result\Result;

class SequentialProcessHandler implements ProcessHandler
{
    /**
     * Run the processes sequentially to gather information.
     * @param FileCollection $filesCollection Collection of files.
     * @param ProcessFactory $processFactory  Process Factory.
     * @param OnSuccess      $onSuccess       The OnSuccess event observer.
     * @return void
     */
    public function process(
        FileCollection $filesCollection,
        ProcessFactory $processFactory,
        OnSuccess $onSuccess
    ): void {
        while ($filesCollection->hasFiles()) {
            $file = $filesCollection->getNextFile();
            $result = new Result($file->getDisplayPath());
            $process = $processFactory->createGitCommitProcess($file);
            $process->start();
            while (!$process->isSuccessful());
            $result->setCommits((int) $process->getOutput());
            $process = $processFactory->createCyclomaticComplexityProcess($file);
            $process->start();
            while (!$process->isSuccessful());
            $result->setComplexity((int) $process->getOutput());
            $onSuccess($result);
        }
    }
}
