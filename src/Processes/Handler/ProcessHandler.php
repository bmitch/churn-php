<?php declare(strict_types = 1);

namespace Churn\Processes\Handler;

use Churn\Collections\FileCollection;
use Churn\Processes\ProcessFactory;
use Illuminate\Support\Collection;

interface ProcessHandler
{
    /**
     * Run the processes to gather information.
     * @param FileCollection $filesCollection Collection of files.
     * @param ProcessFactory $processFactory  Process Factory.
     * @return Collection
     */
    public function process(
        FileCollection $filesCollection,
        ProcessFactory $processFactory
    ): Collection;
}
