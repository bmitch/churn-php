<?php

declare(strict_types=1);

namespace Churn\Process\Handler;

use Churn\Process\ChangesCountInterface;
use Churn\Process\CyclomaticComplexityInterface;
use Churn\Process\Observer\OnSuccess;
use Churn\Process\ProcessFactory;
use Churn\Process\ProcessInterface;
use Churn\Result\Result;
use Generator;

class SequentialProcessHandler implements ProcessHandler
{

    /**
     * Run the processes sequentially to gather information.
     *
     * @param Generator $filesFinder Collection of files.
     * @param ProcessFactory $processFactory Process Factory.
     * @param OnSuccess $onSuccess The OnSuccess event observer.
     */
    public function process(Generator $filesFinder, ProcessFactory $processFactory, OnSuccess $onSuccess): void
    {
        foreach ($filesFinder as $file) {
            $result = new Result($file->getDisplayPath());
            $processes = [
                $processFactory->createChangesCountProcess($file),
                $processFactory->createCyclomaticComplexityProcess($file),
            ];

            foreach ($processes as $process) {
                $this->executeProcess($process, $result);
            }

            $onSuccess($result);
        }
    }

    /**
     * Execute a process and save the result when it's done.
     *
     * @param ProcessInterface $process The process to execute.
     * @param Result $result The result to complete.
     */
    private function executeProcess(ProcessInterface $process, Result $result): Result
    {
        $process->start();

        while (!$process->isSuccessful());

        if ($process instanceof ChangesCountInterface) {
            $result->setCommits($process->countChanges());
        }

        if ($process instanceof CyclomaticComplexityInterface) {
            $result->setComplexity($process->getCyclomaticComplexity());
        }

        return $result;
    }
}
