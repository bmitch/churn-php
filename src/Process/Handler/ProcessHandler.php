<?php

declare(strict_types=1);

namespace Churn\Process\Handler;

use Churn\Process\ProcessFactory;
use Generator;

/**
 * @internal
 */
interface ProcessHandler
{
    /**
     * Run the processes to gather information.
     *
     * @param Generator $filesFinder Collection of files.
     * @param ProcessFactory $processFactory Process Factory.
     * @psalm-param Generator<\Churn\File\File> $filesFinder
     */
    public function process(Generator $filesFinder, ProcessFactory $processFactory): void;
}
