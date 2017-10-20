<?php declare(strict_types = 1);

namespace Churn\Managers;

use Churn\Collections\FileCollection;
use Churn\Factories\ProcessFactory;
use Illuminate\Support\Collection;

class ProcessManager
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
     * Run the processes to gather information.
     * @param FileCollection $filesCollection      Collection of files.
     * @param ProcessFactory $processFactory       Process Factory.
     * @param integer        $numberOfParallelJobs Number of parallel jobs to run.
     * @return Collection
     */
    public function process(
        FileCollection $filesCollection,
        ProcessFactory $processFactory,
        int $numberOfParallelJobs
    ): Collection {
        $this->filesCollection = $filesCollection;
        $this->processFactory = $processFactory;
        $this->runningProcesses = new Collection;
        $this->completedProcessesArray = [];
        while ($filesCollection->hasFiles() || $this->runningProcesses->count()) {
            $this->getProcessResults($numberOfParallelJobs);
        }
        return new Collection($this->completedProcessesArray);
    }

    /**
     * Get the results of the processes.
     * @param integer $numberOfParallelJobs Number of parallel jobs to run.
     * @return void
     */
    private function getProcessResults(int $numberOfParallelJobs)
    {
        for ($index = $this->runningProcesses->count();
             $this->filesCollection->hasFiles() > 0 && $index < $numberOfParallelJobs;
             $index++) {
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
            }
        }
    }
}
