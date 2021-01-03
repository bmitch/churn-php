<?php declare(strict_types = 1);

namespace Churn\Process;

interface CyclomaticComplexityInterface extends ProcessInterface
{

    /**
     * Returns the cyclomatic complexity of a file.
     */
    public function getCyclomaticComplexity(): int;
}
