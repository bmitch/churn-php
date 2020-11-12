<?php declare(strict_types = 1);

namespace Churn\Process;

use Churn\File\File;

interface ProcessInterface
{
    /**
     * Start the process.
     * @return void
     */
    public function start(): void;

    /**
     * Determines if the process was successful.
     * @return boolean
     * @throws ProcessFailedException If the process failed.
     */
    public function isSuccessful(): bool;

    /**
     * Gets the file name of the file the process
     * is being executed on.
     * @return string
     */
    public function getFilename(): string;

    /**
     * Gets the file the process is being executed on.
     * @return File
     */
    public function getFile(): File;
}
