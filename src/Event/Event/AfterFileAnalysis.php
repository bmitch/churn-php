<?php

declare(strict_types=1);

namespace Churn\Event\Event;

use Churn\Event\Event;

interface AfterFileAnalysis extends Event
{

    /**
     * Returns the absolute path of the file.
     */
    public function getFilePath(): string;

    /**
     * Returns the number of times the file has been changed.
     */
    public function getNumberOfChanges(): int;

    /**
     * Returns the cyclomatic complexity of the file.
     */
    public function getCyclomaticComplexity(): int;
}
