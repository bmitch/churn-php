<?php declare(strict_types = 1);

namespace Churn\Process\Handler;

use Churn\Collections\FileCollection;
use Churn\Process\ChurnProcess;
use Churn\Process\Observer\OnSuccess;
use Churn\Process\ProcessFactory;
use Churn\Result\Result;
use Illuminate\Support\Collection;

class ParallelProcessHandler implements ProcessHandler
{
    /**
     * Collection of running processes.
     * @var Collection
     */
    private $runningProcesses;

    /**
     * Collection of files.
     * @var FileCollection
     */
    private $filesCollection;

    /**
     * Array of completed processes.
     * @var array
     */
    private $completedProcessesArray;

    /**
     * Process Factory.
     * @var ProcessFactory
     */
    private $processFactory;

    /**
     * Number of parallel jobs to run.
     * @var integer
     */
    private $numberOfParallelJobs;

    /**
     * ProcessManager constructor.
     * @param int $numberOfParallelJobs Number of parallel jobs to run.
     */
    public function __construct(int $numberOfParallelJobs)
    {
        $this->numberOfParallelJobs = $numberOfParallelJobs;
    }

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
    ): void {
        $this->filesCollection = $filesCollection;
        $this->processFactory = $processFactory;
        $this->runningProcesses = new Collection;
        $this->completedProcessesArray = [];
        while ($filesCollection->hasFiles() || $this->runningProcesses->count()) {
            $this->doProcess($this->numberOfParallelJobs, $onSuccess);
        }
    }

    /**
     * Process files in parallel.
     * @param integer   $numberOfParallelJobs Number of parallel jobs to run.
     * @param OnSuccess $onSuccess            The OnSuccess event observer.
     * @return void
     */
    private function doProcess(int $numberOfParallelJobs, OnSuccess $onSuccess): void
    {
        $index = $this->runningProcesses->count();
        for (; $index < $numberOfParallelJobs && $this->filesCollection->hasFiles() > 0; $index++) {
            $file = $this->filesCollection->getNextFile();
            $process = $this->processFactory->createGitCommitProcess($file);
            $process->start();
            $this->runningProcesses->put($process->getKey(), $process);
            $process = $this->processFactory->createCyclomaticComplexityProcess($file);
            $process->start();
            $this->runningProcesses->put($process->getKey(), $process);
        }

        foreach ($this->runningProcesses as $file => $process) {
            if ($process->isSuccessful()) {
                $this->runningProcesses->forget($process->getKey());
                $this->sendEventIfComplete($this->getResult($process), $onSuccess);
            }
        }
    }

    /**
     * Returns the result of processes for a file.
     * @param ChurnProcess $process A successful process.
     * @return Result
     */
    private function getResult(ChurnProcess $process): Result
    {
        if (!isset($this->completedProcessesArray[$process->getFileName()])) {
            $this->completedProcessesArray[$process->getFileName()] = new Result($process->getFileName());
        }
        $result = $this->completedProcessesArray[$process->getFileName()];
        switch ($process->getType()) {
            case 'GitCommitProcess':
                $result->setCommits((int) $process->getOutput());
                break;
            case 'CyclomaticComplexityProcess':
                $result->setComplexity((int) $process->getOutput());
                break;
            default:
                // nothing to do
                break;
        }

        return $result;
    }

    /**
     * @param Result    $result    The result of the processes for a file.
     * @param OnSuccess $onSuccess The OnSuccess event observer.
     * @return void
     */
    private function sendEventIfComplete(Result $result, OnSuccess $onSuccess): void
    {
        if ($result->isComplete()) {
            $onSuccess($result);
        }
    }
}
