<?php declare(strict_types = 1);

namespace Churn\Process\Observer;

use Churn\Values\File;

interface OnSuccess
{
    /**
     * Triggers an event when a file is successfully processed.
     * @param File $file The file successfully processed.
     * @return void
     */
    public function __invoke(File $file): void;
}
