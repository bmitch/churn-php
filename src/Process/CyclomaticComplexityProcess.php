<?php

declare(strict_types=1);

namespace Churn\Process;

/**
 * @internal
 */
final class CyclomaticComplexityProcess extends ChurnProcess implements CyclomaticComplexityInterface
{
    /**
     * Returns the cyclomatic complexity of a file.
     */
    #[\Override]
    public function getCyclomaticComplexity(): int
    {
        return (int) $this->getOutput();
    }
}
