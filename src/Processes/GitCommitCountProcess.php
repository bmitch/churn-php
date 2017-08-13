<?php declare(strict_types = 1);


namespace Churn\Processes;

use Churn\Values\File;
use Symfony\Component\Process\Process;

class GitCommitCountProcess
{
    /**
     * The Symfony Process Component.
     * @var Process
     */
    private $process;

    /**
     * GitCommitCountProcess constructor.
     * @param File $file The file the process is being executed on.
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Start the process.
     * @return void
     */
    public function start()
    {
        $command = $this->getCommandString();
        $this->process = new Process($command);
        $this->process->start();
    }

    /**
     * Determines if the process was successful.
     * @return boolean
     */
    public function isSuccessful(): bool
    {
        return $this->process->isSuccessful();
    }

    /**
     * Gets the output of the process.
     * @return string
     */
    public function getOutput(): string
    {
        return $this->process->getOutput();
    }

    /**
     * Gets the file name of the file the process
     * is being executed on.
     * @return string
     */
    public function getFilename(): string
    {
        return $this->file->getDisplayPath();
    }

    /**
     * Gets a unique key used for storing the process in data structures.
     * @return string
     */
    public function getKey(): string
    {
        return 'GitCommitCountProcess' . $this->file->getFullPath();
    }

    /**
     * Get the type of this process.
     * @return string
     */
    public function getType(): string
    {
        return 'GitCommitCountProcess';
    }

    /**
     * The process command.
     * @return string
     */
    private function getCommandString(): string
    {
        return 'git -C ' . getcwd() . " log --name-only --pretty=format: " . $this->file->getFullPath(). " | sort | uniq -c | sort -nr";
    }
}
