<?php

declare(strict_types=1);

namespace Churn\Event\Event;

use Churn\Result\ResultAccumulator;

/**
 * @internal
 */
final class AfterAnalysisEvent implements AfterAnalysis
{

    /**
     * @var ResultAccumulator
     */
    private $resultAccumulator;

    /**
     * @param ResultAccumulator $resultAccumulator The results report.
     */
    public function __construct(ResultAccumulator $resultAccumulator)
    {
        $this->resultAccumulator = $resultAccumulator;
    }

    /**
     * Returns the total number of files analysed.
     */
    public function getNumberOfFiles(): int
    {
        return $this->resultAccumulator->getNumberOfFiles();
    }

    /**
     * Returns the max number of changes among the analysed files.
     */
    public function getMaxNumberOfChanges(): int
    {
        return $this->resultAccumulator->getMaxCommits();
    }

    /**
     * Returns the max cyclomatic complexity among the analysed files.
     */
    public function getMaxCyclomaticComplexity(): int
    {
        return $this->resultAccumulator->getMaxComplexity();
    }

    /**
     * Returns the highest score among the analysed files.
     */
    public function getMaxScore(): ?float
    {
        return $this->resultAccumulator->getMaxScore();
    }
}
