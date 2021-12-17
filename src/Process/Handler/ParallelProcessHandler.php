<?php

declare(strict_types=1);

namespace Churn\Process\Handler;

use Churn\Event\Broker;
use Churn\Event\Event\AfterFileAnalysisEvent;
use Churn\File\File;
use Churn\Process\ProcessFactory;
use Churn\Process\ProcessInterface;
use Churn\Result\Result;
use Generator;

/**
 * @internal
 */
class ParallelProcessHandler extends BaseProcessHandler
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
     * @var Broker
     */
    private $broker;

    /**
     * @param integer $numberOfParallelJobs Number of parallel jobs to run.
     * @param Broker $broker The event broker.
     */
    public function __construct(int $numberOfParallelJobs, Broker $broker)
    {
        $this->numberOfParallelJobs = $numberOfParallelJobs;
        $this->completedProcesses = [];
        $this->broker = $broker;
    }

    /**
     * Run the processes to gather information.
     *
     * @param Generator $filesFinder Collection of files.
     * @param ProcessFactory $processFactory Process Factory.
     * @psalm-param Generator<\Churn\File\File> $filesFinder
     */
    public function process(Generator $filesFinder, ProcessFactory $processFactory): void
    {
        $pool = [];

        foreach ($filesFinder as $file) {
            while (\count($pool) >= $this->numberOfParallelJobs) {
                $this->checkRunningProcesses($pool);
            }

            $this->addToPool($pool, $file, $processFactory);
        }

        while (\count($pool) > 0) {
            $this->checkRunningProcesses($pool);
        }
    }

    /**
     * @param array<ProcessInterface> $pool Pool of processes.
     */
    private function checkRunningProcesses(array &$pool): void
    {
        foreach ($pool as $key => $process) {
            if (!$process->isSuccessful()) {
                continue;
            }

            unset($pool[$key]);
            $this->sendEventIfComplete($this->getResult($process));
        }
    }

    /**
     * @param array<ProcessInterface> $pool Pool of processes.
     * @param File $file The file to process.
     * @param ProcessFactory $processFactory Process Factory.
     */
    private function addToPool(array &$pool, File $file, ProcessFactory $processFactory): void
    {
        $i = 0;
        foreach ($processFactory->createProcesses($file) as $process) {
            $process->start();
            $pool["$i:" . $file->getDisplayPath()] = $process;
            $i++;
        }
    }

    /**
     * Returns the result of processes for a file.
     *
     * @param ProcessInterface $process A successful process.
     */
    private function getResult(ProcessInterface $process): Result
    {
        $key = $process->getFile()->getDisplayPath();
        $this->completedProcesses[$key] = $this->completedProcesses[$key] ?? new Result($process->getFile());

        return $this->saveResult($process, $this->completedProcesses[$key]);
    }

    /**
     * @param Result $result The result of the processes for a file.
     */
    private function sendEventIfComplete(Result $result): void
    {
        if (!$result->isComplete()) {
            return;
        }

        unset($this->completedProcesses[$result->getFile()->getDisplayPath()]);
        $this->broker->notify(new AfterFileAnalysisEvent($result));
    }
}
