<?php

declare(strict_types=1);

namespace Churn\Event\Event;

use Churn\Event\Event;

interface AfterAnalysis extends Event
{
    /**
     * Returns the total number of files analysed.
     */
    public function getNumberOfFiles(): int;

    /**
     * Returns the max number of changes among the analysed files.
     */
    public function getMaxNumberOfChanges(): int;

    /**
     * Returns the max cyclomatic complexity among the analysed files.
     */
    public function getMaxCyclomaticComplexity(): int;

    /**
     * Returns the highest score among the analysed files.
     */
    public function getMaxScore(): ?float;
}
