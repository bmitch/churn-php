<?php declare(strict_types = 1);

namespace Churn\Process\Handler;

use Churn\Collections\FileCollection;
use Churn\Process\Observer\OnSuccess;
use Churn\Process\ProcessFactory;
use Churn\Values\File;
use function count;
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
     * @return Collection
     */
    public function process(
        FileCollection $filesCollection,
        ProcessFactory $processFactory,
        OnSuccess $onSuccess
    ): Collection {
        $this->filesCollection = $filesCollection;
        $this->processFactory = $processFactory;
        $this->runningProcesses = new Collection;
        $this->completedProcessesArray = [];
        while ($filesCollection->hasFiles() || $this->runningProcesses->count()) {
            $this->getProcessResults($this->numberOfParallelJobs, $onSuccess);
        }
        return new Collection($this->completedProcessesArray);
    }

    /**
     * Get the results of the processes.
     * @param integer   $numberOfParallelJobs Number of parallel jobs to run.
     * @param OnSuccess $onSuccess            The OnSuccess event observer.
     * @return void
     */
    private function getProcessResults(int $numberOfParallelJobs, OnSuccess $onSuccess): void
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
                $this->completedProcessesArray[$process->getFileName()][$process->getType()] = $process;
                $this->sendEventIfComplete($process->getFile(), $onSuccess);
            }
        }
    }

    /**
     * @param File      $file      The file processed.
     * @param OnSuccess $onSuccess The OnSuccess event observer.
     * @return void
     */
    private function sendEventIfComplete(File $file, OnSuccess $onSuccess): void
    {
        if (count($this->completedProcessesArray[$file->getDisplayPath()]) === 2) {
            $onSuccess($file);
        }
    }
}
