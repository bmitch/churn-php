<?php declare(strict_types = 1);

namespace Churn\Results;

use Illuminate\Support\Collection;
use Churn\Values\Config;

class ResultCollection extends Collection
{
    /**
     * Order results by their score in descending order.
     * @return self
     */
    public function orderByScoreDesc(): self
    {
        return $this->sortByDesc(function (Result $result) {
            return $result->getScore();
        });
    }

    /**
     * Normalize results against config.
     * @param  Config $config
     * @return self
     */
    public function normalizeAgainst(Config $config): self
    {
        $minScore = $config->getMinScoreToShow();

        return $this->orderByScoreDesc()
            ->when($minScore !== 0, $this->filterByMinScore($minScore))
            ->take($config->getFilesToShow());
    }

    /**
     * Filter by min score.
     *
     * @param  int      $minScore
     * @return \Closure
     */
    private function filterByMinScore($minScore): \Closure
    {
        return function (ResultCollection $results) use ($minScore) {
            return $results->filter(function (Result $result) use ($minScore) {
                return $result->getScore() >= $minScore;
            });
        };
    }

    /**
     * Override the original toArray() method to remove those disordered indices.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_values(parent::toArray());
    }
}
