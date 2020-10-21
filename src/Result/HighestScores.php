<?php declare(strict_types = 1);

namespace Churn\Result;

use function array_filter;
use SplFixedArray;

class HighestScores
{
    /**
     * @var SplFixedArray
     */
    private $scores;

    /**
     * @param int $maxSize Max number of results to keep.
     */
    public function __construct(int $maxSize)
    {
        $this->scores = new SplFixedArray($maxSize);
    }

    /**
     * Returns the results as a normal PHP array.
     * @return Result[]
     */
    public function toArray(): array
    {
        return array_filter($this->scores->toArray());
    }
    
    /**
     * Add the result if its priority is high enough.
     * @param Result $result The result for a file.
     * @return void
     */
    public function add(Result $result): void
    {
        if ($this->scores[$this->scores->getSize() - 1] !== null
        && $result->getPriority() <= $this->scores[$this->scores->getSize() - 1]->getPriority()) {
            return;
        }

        $this->insertAt($result, $this->findPosition($result));
    }

    /**
     * Returns the position where the result must be inserted.
     * @param Result $result The result for a file.
     * @return integer
     */
    private function findPosition(Result $result): int
    {
        $pos = 0;

        $this->scores->rewind();
        foreach ($this->scores as $score) {
            if ($score === null || $result->getPriority() > $score->getPriority()) {
                break;
            }
            $pos++;
        }

        return $pos;
    }

    /**
     * Inserts the result at a given position and shifts the lower elements.
     * @param Result $result   The result for a file.
     * @param int    $position The position where the result must be inserted.
     * @return void
     */
    private function insertAt(Result $result, int $position): void
    {
        for ($i = $this->scores->getSize() - 1; $i > $position; $i--) {
            $this->scores[$i] = $this->scores[$i - 1];
        }

        $this->scores[$position] = $result;
    }
}
