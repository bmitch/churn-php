<?php declare(strict_types = 1);

namespace Churn\Results;

use function array_map;
use function array_merge;
use function array_values;
use Illuminate\Support\Collection;

class ResultCollection extends Collection
{
    /**
     * Order results by their score in descending order.
     * @return self
     */
    public function orderByScoreDesc(): self
    {
        return $this->sortByDesc(function (Result $result) {
            return $result->getScore($this->maxCommits(), $this->maxComplexity());
        });
    }

    /**
     * Filter the results where their score is >= the provided $score
     * @param float $score Score to filter by.
     * @return static
     */
    public function whereScoreAbove(float $score)
    {
        return $this->filter(function (Result $result) use ($score) {
            return $result->getScore($this->maxCommits(), $this->maxComplexity()) >= $score;
        });
    }

    /**
     * Get the highest number of commits.
     * @return integer
     */
    public function maxCommits(): int
    {
        return $this->max(function (Result $result) {
            return $result->getCommits();
        });
    }

    /**
     * Get the highest complexity.
     * @return integer
     */
    public function maxComplexity(): int
    {
        return $this->max(function (Result $result) {
            return $result->getComplexity();
        });
    }

    /**
     * Override the original toArray() method to remove those disordered indices.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_values(array_map(
            function (Result $result) {
                return array_merge(
                    $result->toArray(),
                    [$result->getScore($this->maxCommits(), $this->maxComplexity())]
                );
            },
            $this->items
        ));
    }
}
