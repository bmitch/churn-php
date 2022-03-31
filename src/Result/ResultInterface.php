<?php

declare(strict_types=1);

namespace Churn\Result;

use Churn\File\File;

/**
 * @internal
 */
interface ResultInterface
{
    /**
     * Return the file.
     */
    public function getFile(): File;

    /**
     * Get the number of changes.
     */
    public function getCommits(): int;

    /**
     * Get the file complexity.
     */
    public function getComplexity(): int;

    /**
     * Get the file priority.
     */
    public function getPriority(): int;

    /**
     * Calculate the score.
     *
     * @param integer $maxCommits The highest number of commits out of any file scanned.
     * @param integer $maxComplexity The maximum complexity out of any file scanned.
     */
    public function getScore(int $maxCommits, int $maxComplexity): float;
}
