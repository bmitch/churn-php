<?php declare(strict_types = 1);

namespace Churn\Result;

use function is_null;
use Webmozart\Assert\Assert;

class Result
{
    /**
     * The file property.
     * @var string
     */
    private $file;

    /**
     * The commits property.
     * @var null|integer
     */
    private $commits;

    /**
     * The complexity property.
     * @var null|integer
     */
    private $complexity;

    /**
     * Class constructor.
     * @param string $file The path of the processed file.
     */
    public function __construct(string $file)
    {
        $this->file = $file;
    }

    /**
     * Get the file path.
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * Indicates the metrics are all set.
     * @return boolean
     */
    public function isComplete(): bool
    {
        return !is_null($this->commits) && !is_null($this->complexity);
    }

    /**
     * @param int $commits Number of changes.
     * @return self
     */
    public function setCommits(int $commits): self
    {
        $this->commits = $commits;

        return $this;
    }

    /**
     * Get the number of changes.
     * @return integer
     */
    public function getCommits(): int
    {
        return $this->commits;
    }

    /**
     * @param int $complexity The file complexity.
     * @return self
     */
    public function setComplexity(int $complexity): self
    {
        $this->complexity = $complexity;

        return $this;
    }

    /**
     * Get the file complexity.
     * @return integer
     */
    public function getComplexity(): int
    {
        return $this->complexity;
    }

    /**
     * Get the file priority.
     * @return integer
     */
    public function getPriority(): int
    {
        return $this->commits * $this->complexity;
    }

    /**
     * Calculate the score.
     * @param int $maxCommits    The highest number of commits out of any file scanned.
     * @param int $maxComplexity The maximum complexity out of any file scanned.
     * @return float
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
        $distanceFromTopRightCorner = sqrt(
            $normalizedHorizontalDistance ** 2
            + $normalizedVerticalDistance ** 2
        );

        /*
         * The resulting value will be between 0 and sqrt(2). A short distance is bad,
         * so in order to end up with a high score, we invert the value by
         * subtracting it from 1.
         */
        return round(1 - $distanceFromTopRightCorner, 3);
        // @codingStandardsIgnoreEnd
    }
}
