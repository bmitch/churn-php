<?php

declare(strict_types=1);

namespace Churn\Event\Event;

use Churn\Result\ResultInterface;

/**
 * @internal
 */
final class AfterFileAnalysisEvent implements AfterFileAnalysis
{
    /**
     * @var ResultInterface
     */
    private $result;

    /**
     * @param ResultInterface $result The result for a file.
     */
    public function __construct(ResultInterface $result)
    {
        $this->result = $result;
    }

    /**
     * Returns the result for a file.
     */
    public function getResult(): ResultInterface
    {
        return $this->result;
    }

    /**
     * Returns the absolute path of the file.
     */
    #[\Override]
    public function getFilePath(): string
    {
        return $this->result->getFile()->getFullPath();
    }

    /**
     * Returns the number of times the file has been changed.
     */
    #[\Override]
    public function getNumberOfChanges(): int
    {
        return $this->result->getCommits();
    }

    /**
     * Returns the cyclomatic complexity of the file.
     */
    #[\Override]
    public function getCyclomaticComplexity(): int
    {
        return $this->result->getComplexity();
    }
}
