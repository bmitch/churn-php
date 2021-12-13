<?php

declare(strict_types=1);

namespace Churn\Result;

use Churn\File\File;
use Webmozart\Assert\Assert;

/**
 * @internal
 */
class Result
{
    /**
     * The file property.
     *
     * @var File
     */
    private $file;

    /**
     * The commits property.
     *
     * @var integer
     */
    private $commits;

    /**
     * The complexity property.
     *
     * @var integer
     */
    private $complexity;

    /**
     * @param File $file The processed file.
     */
    public function __construct(File $file)
    {
        $this->file = $file;
        $this->commits = -1;
        $this->complexity = -1;
    }

    /**
     * Return the file.
     */
    public function getFile(): File
    {
        return $this->file;
    }

    /**
     * Indicates whether the metrics are all set.
     */
    public function isComplete(): bool
    {
        return $this->commits > -1 && $this->complexity > -1;
    }

    /**
     * @param integer $commits Number of changes.
     */
    public function setCommits(int $commits): self
    {
        $this->commits = $commits;

        return $this;
    }

    /**
     * Get the number of changes.
     */
    public function getCommits(): int
    {
        return $this->commits;
    }

    /**
     * @param integer $complexity The file complexity.
     */
    public function setComplexity(int $complexity): self
    {
        $this->complexity = $complexity;

        return $this;
    }

    /**
     * Get the file complexity.
     */
    public function getComplexity(): int
    {
        return $this->complexity;
    }

    /**
     * Get the file priority.
     */
    public function getPriority(): int
    {
        return $this->commits * $this->complexity;
    }

    /**
     * Calculate the score.
     *
     * @param integer $maxCommits The highest number of commits out of any file scanned.
     * @param integer $maxComplexity The maximum complexity out of any file scanned.
     * @codingStandardsIgnoreStart
     */
    public function getScore(int $maxCommits, int $maxComplexity): float
    {
        Assert::greaterThan($maxComplexity, 0);
        Assert::greaterThan($maxCommits, 0);

        /*
         * Calculate the horizontal and vertical distance from the "top right"
         * corner.
         */
        $horizontalDistance = $maxCommits - $this->getCommits();
        $verticalDistance = $maxComplexity - $this->getComplexity();

        /*
         * Normalize these values over time, we first divide by the maximum
         * values, to always end up with distances between 0 and 1.
         */
        $normalizedHorizontalDistance = $horizontalDistance / $maxCommits;
        $normalizedVerticalDistance = $verticalDistance / $maxComplexity;

        /*
         * Calculate the distance of this class from the "top right" corner,
         * using the simple formula A^2 + B^2 = C^2; or: C = sqrt(A^2 + B^2)).
         */
        $distanceFromTopRightCorner = \sqrt(
            $normalizedHorizontalDistance ** 2
            + $normalizedVerticalDistance ** 2
        );

        /*
         * The resulting value will be between 0 and sqrt(2). A short distance is bad,
         * so in order to end up with a high score, we invert the value by
         * subtracting it from 1.
         */
        return \round(1 - $distanceFromTopRightCorner, 3);
        // @codingStandardsIgnoreEnd
    }
}
