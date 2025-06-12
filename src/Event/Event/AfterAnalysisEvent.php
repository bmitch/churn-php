<?php

declare(strict_types=1);

namespace Churn\Event\Event;

use Churn\Result\ResultReporter;

/**
 * @internal
 */
final class AfterAnalysisEvent implements AfterAnalysis
{
    /**
     * @var ResultReporter
     */
    private $resultReporter;

    /**
     * @param ResultReporter $resultReporter The results report.
     */
    public function __construct(ResultReporter $resultReporter)
    {
        $this->resultReporter = $resultReporter;
    }

    /**
     * Returns the total number of files analysed.
     */
    #[\Override]
    public function getNumberOfFiles(): int
    {
        return $this->resultReporter->getNumberOfFiles();
    }

    /**
     * Returns the max number of changes among the analysed files.
     */
    #[\Override]
    public function getMaxNumberOfChanges(): int
    {
        return $this->resultReporter->getMaxCommits();
    }

    /**
     * Returns the max cyclomatic complexity among the analysed files.
     */
    #[\Override]
    public function getMaxCyclomaticComplexity(): int
    {
        return $this->resultReporter->getMaxComplexity();
    }

    /**
     * Returns the highest score among the analysed files.
     */
    #[\Override]
    public function getMaxScore(): ?float
    {
        return $this->resultReporter->getMaxScore();
    }
}
