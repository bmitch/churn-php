<?php

declare(strict_types=1);

namespace Churn\Process\Handler;

use Churn\Process\ChangesCountInterface;
use Churn\Process\CyclomaticComplexityInterface;
use Churn\Process\ProcessInterface;
use Churn\Result\Result;

/**
 * @internal
 */
abstract class BaseProcessHandler implements ProcessHandler
{

    /**
     * @param ProcessInterface $process A successful process.
     * @param Result $result The result object to hydrate.
     */
    protected function saveResult(ProcessInterface $process, Result $result): Result
    {
        if ($process instanceof ChangesCountInterface) {
            $result->setCommits($process->countChanges());
        }

        if ($process instanceof CyclomaticComplexityInterface) {
            $result->setComplexity($process->getCyclomaticComplexity());
        }

        return $result;
    }
}
