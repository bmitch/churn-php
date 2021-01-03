<?php declare(strict_types = 1);

namespace Churn\Process;

interface ChangesCountInterface extends ProcessInterface
{

    /**
     * Returns the number of changes for a file.
     */
    public function countChanges(): int;
}
