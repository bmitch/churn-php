<?php

declare(strict_types=1);

namespace Churn\Process\Handler;

use Churn\File\File;
use Churn\Process\ChangesCountInterface;
use Churn\Process\CyclomaticComplexityInterface;
use Churn\Process\Observer\OnSuccess;
use Churn\Process\ProcessFactory;
use Churn\Process\ProcessInterface;
use Churn\Result\Result;
use Generator;

class ParallelProcessHandler implements ProcessHandler
{

    /**
     * Array of completed processes.
     *
     * @var array<Result>
     */
    private $completedProcesses;

    /**
     * Number of parallel jobs to run.
     *
     * @var integer
     */
    private $numberOfParallelJobs;

    /**
     * ProcessManager constructor.
     *
     * @param integer $numberOfParallelJobs Number of parallel jobs to run.
     */
    public function __construct(int $numberOfParallelJobs)
    {
        $this->numberOfParallelJobs = $numberOfParallelJobs;
        $this->completedProcesses = [];
    }

    /**
     * Run the processes to gather information.
     *
     * @param Generator $filesFinder Collection of files.
     * @param ProcessFactory $processFactory Process Factory.
     * @param OnSuccess $onSuccess The OnSuccess event observer.
     */
    public function process(Generator $filesFinder, ProcessFactory $processFactory, OnSuccess $onSuccess): void
    {
        $pool = [];

        foreach ($filesFinder as $file) {
            while (\count($pool) >= $this->numberOfParallelJobs) {
                $this->checkRunningProcesses($pool, $onSuccess);
            }

            $this->addToPool($pool, $file, $processFactory);
        }

        while (\count($pool) > 0) {
            $this->checkRunningProcesses($pool, $onSuccess);
        }
    }

    /**
     * @param array<ProcessInterface> $pool Pool of processes.
     * @param OnSuccess $onSuccess The OnSuccess event observer.
     */
    private function checkRunningProcesses(array &$pool, OnSuccess $onSuccess): void
    {
        foreach ($pool as $key => $process) {
            if (!$process->isSuccessful()) {
                continue;
            }

            unset($pool[$key]);
            $this->sendEventIfComplete($this->getResult($process), $onSuccess);
        }
    }

    /**
     * @param array<ProcessInterface> $pool Pool of processes.
     * @param File $file The file to process.
     * @param ProcessFactory $processFactory Process Factory.
     */
    private function addToPool(array &$pool, File $file, ProcessFactory $processFactory): void
    {
        $process = $processFactory->createChangesCountProcess($file);
        $process->start();
        $pool['CountChanges:' . $process->getFilename()] = $process;
        $process = $processFactory->createCyclomaticComplexityProcess($file);
        $process->start();
        $pool['Complexity:' . $process->getFilename()] = $process;
    }

    /**
     * Returns the result of processes for a file.
     *
     * @param ProcessInterface $process A successful process.
     */
    private function getResult(ProcessInterface $process): Result
    {
        if (!isset($this->completedProcesses[$process->getFileName()])) {
            $this->completedProcesses[$process->getFileName()] = new Result($process->getFileName());
        }

        $result = $this->completedProcesses[$process->getFileName()];

        if ($process instanceof ChangesCountInterface) {
            $result->setCommits($process->countChanges());
        }

        if ($process instanceof CyclomaticComplexityInterface) {
            $result->setComplexity($process->getCyclomaticComplexity());
        }

        return $result;
    }

    /**
     * @param Result $result The result of the processes for a file.
     * @param OnSuccess $onSuccess The OnSuccess event observer.
     */
    private function sendEventIfComplete(Result $result, OnSuccess $onSuccess): void
    {
        if (!$result->isComplete()) {
            return;
        }

        unset($this->completedProcesses[$result->getFile()]);
        $onSuccess($result);
    }
}
