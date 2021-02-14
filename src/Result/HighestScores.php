<?php

declare(strict_types=1);

namespace Churn\Result;

use SplFixedArray;

/**
 * @internal
 */
class HighestScores
{

    /**
     * @var SplFixedArray
     */
    private $scores;

    /**
     * @param integer $maxSize Max number of results to keep.
     */
    public function __construct(int $maxSize)
    {
        $this->scores = new SplFixedArray($maxSize);
    }

    /**
     * Returns the results as a normal PHP array.
     *
     * @return array<Result>
     */
    public function toArray(): array
    {
        return \array_filter($this->scores->toArray());
    }

    /**
     * Add the result if its priority is high enough.
     *
     * @param Result $result The result for a file.
     */
    public function add(Result $result): void
    {
        if (
            null !== $this->scores[$this->scores->getSize() - 1]
            && $result->getPriority() <= $this->scores[$this->scores->getSize() - 1]->getPriority()
        ) {
            return;
        }

        $this->insertAt($result, $this->findPosition($result));
    }

    /**
     * Returns the position where the result must be inserted.
     *
     * @param Result $result The result for a file.
     */
    private function findPosition(Result $result): int
    {
        $pos = 0;

        foreach ($this->scores as $score) {
            if (null === $score || $result->getPriority() > $score->getPriority()) {
                break;
            }

            $pos++;
        }

        return $pos;
    }

    /**
     * Inserts the result at a given position and shifts the lower elements.
     *
     * @param Result $result The result for a file.
     * @param integer $position The position where the result must be inserted.
     */
    private function insertAt(Result $result, int $position): void
    {
        for ($i = $this->scores->getSize() - 1; $i > $position; $i--) {
            $this->scores[$i] = $this->scores[$i - 1];
        }

        $this->scores[$position] = $result;
    }
}
