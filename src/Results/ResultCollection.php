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
     * @return array
     */
    public function normalizeAgainst(Config $config): array
    {
        $minScore = $config->getMinScoreToShow();

        return array_values(
            $this->orderByScoreDesc()
                ->when($minScore !== 0, $this->filterByMinScore($minScore))
                ->take($config->getFilesToShow())
                ->toArray()
        );
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
}
