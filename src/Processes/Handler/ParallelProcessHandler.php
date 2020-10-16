<?php declare(strict_types = 1);

namespace Churn\Processes\Handler;

use Churn\Collections\FileCollection;
use Churn\Processes\ProcessFactory;
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
     * @return Collection
     */
    public function process(
        FileCollection $filesCollection,
        ProcessFactory $processFactory
    ): Collection {
        $this->filesCollection = $filesCollection;
        $this->processFactory = $processFactory;
        $this->runningProcesses = new Collection;
        $this->completedProcessesArray = [];
        while ($filesCollection->hasFiles() || $this->runningProcesses->count()) {
            $this->getProcessResults($this->numberOfParallelJobs);
        }
        return new Collection($this->completedProcessesArray);
    }

    /**
     * Get the results of the processes.
     * @param integer $numberOfParallelJobs Number of parallel jobs to run.
     * @return void
     */
    private function getProcessResults(int $numberOfParallelJobs): void
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
            }
        }
    }
}
