<?php declare(strict_types = 1);

namespace Churn\Results;

use Illuminate\Support\Collection;

class ResultCollection extends Collection
{
    /**
     * Order results by their score in descending order.
     * @return self
     */
    public function orderByScoreDesc(): self
    {
        return $this->sortByDesc(function ($result) {
            return $result->getScore();
        });
    }
}
