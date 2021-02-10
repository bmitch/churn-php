<?php

declare(strict_types=1);

namespace Churn\Process\Handler;

use Churn\Process\Observer\OnSuccess;
use Churn\Process\ProcessFactory;
use Churn\Process\ProcessInterface;
use Churn\Result\Result;
use Generator;

class SequentialProcessHandler extends BaseProcessHandler
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

            foreach ($processFactory->createProcesses($file) as $process) {
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
    private function executeProcess(ProcessInterface $process, Result $result): void
    {
        $process->start();

        while (!$process->isSuccessful());

        $this->saveResult($process, $result);
    }
}
