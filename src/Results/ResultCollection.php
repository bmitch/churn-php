<?php declare(strict_types = 1);

namespace Churn\Results;

use Churn\Configuration\Config;
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
     * Normalize results against config.
     * @param  Config $config Config settings.
     * @return self
     */
    public function normalizeAgainst(Config $config): self
    {
        $minScore = $config->getMinScoreToShow();

        return $this->orderByScoreDesc()
            ->filter(
                function (Result $result) use ($minScore) {
                    return $result->getScore($this->maxCommits(), $this->maxComplexity()) >= $minScore;
                }
            )
            ->take($config->getFilesToShow());
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
