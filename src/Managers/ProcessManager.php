<?php declare(strict_types = 1);

namespace Churn\Managers;

use Churn\Collections\FileCollection;
use Churn\Factories\ProcessFactory;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Tightenco\Collect\Support\Collection;

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
     * Process Factory.
     * @var ProcessFactory
     */
    private $processFactory;

    /**
     * Total count of files needed to be processed.
     * @var int
     */
    private $totalFilesToProcessCount = 0;

    /**
     * To show or not progress bar while processing files.
     * @var bool
     */
    private $showProgressBar = false;

    /**
     * Symfony progress bar
     * @var ProgressBar
     */
    private $progressBar;

    /**
     * Run the processes to gather information.
     * @param FileCollection  $filesCollection      Collection of files.
     * @param ProcessFactory  $processFactory       Process Factory.
     * @param integer         $numberOfParallelJobs Number of parallel jobs to run.
     * @param OutputInterface $output               Symfony output
     * @param bool            $showProgressBar      To show or not progress bar while processing files.
     * @return Collection
     */
    public function process(
        FileCollection $filesCollection,
        ProcessFactory $processFactory,
        int $numberOfParallelJobs,
        OutputInterface $output,
        bool $showProgressBar = false
    ): Collection {
        $this->filesCollection = $filesCollection;
        $this->processFactory = $processFactory;
        $this->runningProcesses = new Collection;
        $this->completedProcessesArray = [];
        $this->totalFilesToProcessCount = $this->filesCollection->count();
        $this->showProgressBar = $showProgressBar;
        $this->progressBar = new ProgressBar($output, 100);

        if ($this->showProgressBar) {
            ProgressBar::setFormatDefinition('custom', '[%bar%] %percent:3s%% -- %message%');
            $this->progressBar->setFormat('custom');
            $output->write("Progress:".PHP_EOL);
            $this->setProgressBarMessage();
            $this->progressBar->start();
        }

        while ($filesCollection->hasFiles() || $this->runningProcesses->count()) {
            $this->getProcessResults($numberOfParallelJobs);
        }

        if ($this->showProgressBar) {
            $this->progressBar->finish();
        }

        return new Collection($this->completedProcessesArray);
    }

    /**
     * Returns count of currently processed files.
     *
     * @return int
     */
    private function getCompletedProcessesCount(): int
    {
        return count($this->completedProcessesArray);
    }

    /**
     * Get the results of the processes.
     * @param integer $numberOfParallelJobs Number of parallel jobs to run.
     * @return void
     */
    private function getProcessResults(int $numberOfParallelJobs): void
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

                if ($this->showProgressBar) {
                    $currentStep = intval(floor(($this->getCompletedProcessesCount() / $this->totalFilesToProcessCount) * 100));
                    $this->setProgressBarMessage();
                    $this->progressBar->setProgress($currentStep);
                }
            }
        }
    }

    /**
     * Sets progress message for progress bar
     */
    private function setProgressBarMessage(): void
    {
        $this->progressBar->setMessage(sprintf("Files: %d of %d", $this->getCompletedProcessesCount(), $this->totalFilesToProcessCount));
    }
}
