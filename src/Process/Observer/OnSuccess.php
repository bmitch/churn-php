<?php

declare(strict_types=1);

namespace Churn\Process\Observer;

use Churn\Result\Result;

interface OnSuccess
{

    /**
     * Triggers an event when a file is successfully processed.
     *
     * @param Result $result The result for a file.
     */
    public function __invoke(Result $result): void;
}
