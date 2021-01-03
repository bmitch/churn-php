<?php declare(strict_types = 1);

namespace Churn\Process;

class CyclomaticComplexityProcess extends ChurnProcess implements CyclomaticComplexityInterface
{

    /**
     * Returns the cyclomatic complexity of a file.
     */
    public function getCyclomaticComplexity(): int
    {
        return (int) $this->getOutput();
    }
}
