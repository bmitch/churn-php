<?php declare(strict_types = 1);

namespace Churn\Results;

use Illuminate\Contracts\Support\Arrayable;
use Webmozart\Assert\Assert;

class Result implements Arrayable
{
    /**
     * The file property.
     * @var string
     */
    private $file;

    /**
     * The commits property.
     * @var integer
     */
    private $commits;

    /**
     * The complexity property.
     * @var integer
     */
    private $complexity;

    /**
     * Class constructor.
     * @param array $data Data to store in the object.
     */
    public function __construct(array $data)
    {
        $this->file       = $data['file'];
        $this->commits    = $data['commits'];
        $this->complexity = $data['complexity'];
    }

    /**
     * Get the file property.
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * Get the file property.
     * @return integer
     */
    public function getCommits(): int
    {
        return $this->commits;
    }

    /**
     * Get the file property.
     * @return integer
     */
    public function getComplexity(): int
    {
        return $this->complexity;
    }

    /**
     * Calculate the score.
     * @return float
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
        return 1 - $distanceFromTopRightCorner;
    }

    /**
     * Output results to an array.
     * @return array
     */
    public function toArray(): array
    {
        return [
            $this->getFile(),
            $this->getCommits(),
            $this->getComplexity()
        ];
    }
}
