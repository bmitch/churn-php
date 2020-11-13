<?php declare(strict_types = 1);

namespace Churn\Result;

class ResultAccumulator
{
    /**
     * @var integer
     */
    private $maxCommits;

    /**
     * @var integer
     */
    private $maxComplexity;

    /**
     * @var integer
     */
    private $numberOfFiles;

    /**
     * @var HighestScores
     */
    private $highestScores;

    /**
     * @var float|null
     */
    private $minScore;

    /**
     * @param int        $maxSize  The maximum number of files to display in the results table.
     * @param float|null $minScore The minimum score a file need to display in the results table.
     */
    public function __construct(int $maxSize, ?float $minScore)
    {
        $this->maxCommits = 0;
        $this->maxComplexity = 0;
        $this->numberOfFiles = 0;
        $this->minScore = $minScore;
        $this->highestScores = new HighestScores($maxSize);
    }

    /**
     * @param Result $result The result for a file.
     * @return void
     */
    public function add(Result $result): void
    {
        if ($result->getPriority() === 0) {
            return;
        }
        $this->numberOfFiles++;
        if ($result->getCommits() > $this->maxCommits) {
            $this->maxCommits = $result->getCommits();
        }
        if ($result->getComplexity() > $this->maxComplexity) {
            $this->maxComplexity = $result->getComplexity();
        }
        $this->highestScores->add($result);
    }

    /**
     * Returns the maximum number of changes for a file.
     * @return integer
     */
    public function getMaxCommits(): int
    {
        return $this->maxCommits;
    }

    /**
     * Returns the maximum complexity for a file.
     * @return integer
     */
    public function getMaxComplexity(): int
    {
        return $this->maxComplexity;
    }

    /**
     * Returns the number of files processed.
     * @return integer
     */
    public function getNumberOfFiles(): int
    {
        return $this->numberOfFiles;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $rows = [];
        foreach ($this->highestScores->toArray() as $result) {
            $score = $result->getScore($this->maxCommits, $this->maxComplexity);
            if ($this->minScore !== null && $score < $this->minScore) {
                break;
            }
            $rows[] = [
                $result->getFile(),
                $result->getCommits(),
                $result->getComplexity(),
                $score,
            ];
        }
        return $rows;
    }
}
