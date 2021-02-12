<?php

declare(strict_types=1);

namespace Churn\Event\Event;

use Churn\Event\Event;
use Churn\Result\ResultAccumulator;

class AfterAnalysisEvent implements Event
{

    /**
     * @var ResultAccumulator
     */
    private $resultAccumulator;

    /**
     * @param ResultAccumulator $resultAccumulator The results report.
     */
    public function __construct(ResultAccumulator $resultAccumulator)
    {
        $this->resultAccumulator = $resultAccumulator;
    }

    /**
     * Return the results report.
     */
    public function getResultAccumulator(): ResultAccumulator
    {
        return $this->resultAccumulator;
    }
}
