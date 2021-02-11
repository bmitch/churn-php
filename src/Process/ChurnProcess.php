<?php

declare(strict_types=1);

namespace Churn\Process;

use Churn\File\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

abstract class ChurnProcess implements ProcessInterface
{

    /**
     * The file the process will be executed on.
     *
     * @var File
     */
    protected $file;

    /**
     * The Symfony Process Component.
     *
     * @var Process
     */
    protected $process;

    /**
     * Class constructor.
     *
     * @param File $file The file the process is being executed on.
     * @param Process $process The process being executed on the file.
     */
    public function __construct(File $file, Process $process)
    {
        $this->file = $file;
        $this->process = $process;
    }

    /**
     * Start the process.
     */
    public function start(): void
    {
        $this->process->start();
    }

    /**
     * Determines if the process was successful.
     *
     * @throws ProcessFailedException If the process failed.
     */
    public function isSuccessful(): bool
    {
        $exitCode = $this->process->getExitCode();

        if ($exitCode > 0) {
            throw new ProcessFailedException($this->process);
        }

        return 0 === $exitCode;
    }

    /**
     * Gets the file the process is being executed on.
     */
    public function getFile(): File
    {
        return $this->file;
    }

    /**
     * Gets the output of the process.
     */
    protected function getOutput(): string
    {
        return $this->process->getOutput();
    }
}
