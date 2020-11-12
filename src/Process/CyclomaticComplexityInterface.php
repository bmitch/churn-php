<?php declare(strict_types = 1);

namespace Churn\Process;

interface CyclomaticComplexityInterface extends ProcessInterface
{
    /**
     * Returns the cyclomatic complexity of a file.
     * @return integer
     */
    public function getCyclomaticComplexity(): int;
}
