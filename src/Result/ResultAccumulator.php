<?php

declare(strict_types=1);

namespace Churn\Result;

use Churn\Event\Event\AfterFileAnalysis as AfterFileAnalysisEvent;
use Churn\Event\Event\AfterFileAnalysisEvent as AfterFileAnalysisEventWithResult;
use Churn\Event\Subscriber\AfterFileAnalysis;

/**
 * @internal
 */
class ResultAccumulator implements AfterFileAnalysis
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
     * @param integer $maxSize The maximum number of files to display in the results table.
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
     * @param AfterFileAnalysisEvent $event The event triggered when the analysis of a file is done.
     */
    public function onAfterFileAnalysis(AfterFileAnalysisEvent $event): void
    {
        if (!($event instanceof AfterFileAnalysisEventWithResult)) {
            return;
        }

        $this->add($event->getResult());
    }

    /**
     * @param Result $result The result for a file.
     */
    public function add(Result $result): void
    {
        if (0 === $result->getPriority()) {
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
     */
    public function getMaxCommits(): int
    {
        return $this->maxCommits;
    }

    /**
     * Returns the maximum complexity for a file.
     */
    public function getMaxComplexity(): int
    {
        return $this->maxComplexity;
    }

    /**
     * Returns the number of files processed.
     */
    public function getNumberOfFiles(): int
    {
        return $this->numberOfFiles;
    }

    /**
     * Returns the highest score.
     */
    public function getMaxScore(): ?float
    {
        $result = $this->highestScores->toArray()[0] ?? null;

        return null === $result
            ? null
            : $result->getScore($this->maxCommits, $this->maxComplexity);
    }

    /**
     * @return array<array<float|int|string>>
     */
    public function toArray(): array
    {
        $rows = [];

        foreach ($this->highestScores->toArray() as $result) {
            $score = $result->getScore($this->maxCommits, $this->maxComplexity);

            if (null !== $this->minScore && $score < $this->minScore) {
                break;
            }

            $rows[] = [
                $result->getFile()->getDisplayPath(),
                $result->getCommits(),
                $result->getComplexity(),
                $score,
            ];
        }

        return $rows;
    }
}
