<?php declare(strict_types = 1);

namespace Churn\Process;

use Churn\File\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

abstract class ChurnProcess
{
    /**
     * The file the process will be executed on.
     * @var File
     */
    protected $file;

    /**
     * The Symfony Process Component.
     * @var Process
     */
    protected $process;

    /**
     * Class constructor.
     * @param File    $file    The file the process is being executed on.
     * @param Process $process The process being executed on the file.
     */
    public function __construct(File $file, Process $process)
    {
        $this->file = $file;
        $this->process = $process;
    }

    /**
     * Get the type of this process.
     * @return string
     */
    abstract public function getType(): string;

    /**
     * Start the process.
     * @return void
     */
    public function start(): void
    {
        $this->process->start();
    }

    /**
     * Determines if the process was successful.
     * @return boolean
     * @throws ProcessFailedException If the process failed.
     */
    public function isSuccessful(): bool
    {
        $exitCode = $this->process->getExitCode();
        if ($exitCode > 0) {
            throw new ProcessFailedException($this->process);
        }

        return $exitCode === 0;
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
     * Gets the file the process is being executed on.
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }

    /**
     * Gets a unique key used for storing the process in data structures.
     * @return string
     */
    public function getKey(): string
    {
        return $this->getType() . $this->file->getFullPath();
    }
}
