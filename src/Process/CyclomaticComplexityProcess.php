<?php declare(strict_types = 1);

namespace Churn\Process;

class CyclomaticComplexityProcess extends ChurnProcess
{
    /**
     * Gets the type of the process.
     * @return string
     */
    public function getType(): string
    {
        return 'CyclomaticComplexity';
    }

    /**
     * Returns the cyclomatic complexity of a file.
     * @return integer
     */
    public function getCyclomaticComplexity(): int
    {
        return (int) $this->getOutput();
    }
}
