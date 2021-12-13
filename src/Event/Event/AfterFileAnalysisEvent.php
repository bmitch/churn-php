<?php

declare(strict_types=1);

namespace Churn\Event\Event;

use Churn\Result\Result;

/**
 * @internal
 */
final class AfterFileAnalysisEvent implements AfterFileAnalysis
{
    /**
     * @var Result
     */
    private $result;

    /**
     * @param Result $result The result for a file.
     */
    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    /**
     * Returns the result for a file.
     */
    public function getResult(): Result
    {
        return $this->result;
    }

    /**
     * Returns the absolute path of the file.
     */
    public function getFilePath(): string
    {
        return $this->result->getFile()->getFullPath();
    }

    /**
     * Returns the number of times the file has been changed.
     */
    public function getNumberOfChanges(): int
    {
        return $this->result->getCommits();
    }

    /**
     * Returns the cyclomatic complexity of the file.
     */
    public function getCyclomaticComplexity(): int
    {
        return $this->result->getComplexity();
    }
}
