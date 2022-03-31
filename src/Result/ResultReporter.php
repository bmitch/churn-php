<?php

declare(strict_types=1);

namespace Churn\Result;

/**
 * @internal
 */
interface ResultReporter
{
    /**
     * Returns the maximum number of changes for a file.
     */
    public function getMaxCommits(): int;

    /**
     * Returns the maximum complexity for a file.
     */
    public function getMaxComplexity(): int;

    /**
     * Returns the number of files processed.
     */
    public function getNumberOfFiles(): int;

    /**
     * Returns the highest score.
     */
    public function getMaxScore(): ?float;
}
