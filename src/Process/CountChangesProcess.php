<?php declare(strict_types = 1);

namespace Churn\Process;

class CountChangesProcess extends ChurnProcess
{
    /**
     * Gets the type of the process.
     * @return string
     */
    public function getType(): string
    {
        return 'CountChanges';
    }

    /**
     * Returns the number of changes for a file.
     * @return integer
     */
    public function countChanges(): int
    {
        return (int) $this->getOutput();
    }
}
