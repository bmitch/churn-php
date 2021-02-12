<?php

declare(strict_types=1);

namespace Churn\Event\Event;

use Churn\Event\Event;
use Churn\Result\Result;

class AfterFileAnalysisEvent implements Event
{

    /**
     * @var Result
     */
    private $result;

    /**
     * @param Result $result The result for a file.
     */
    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    /**
     * Return the result for a file.
     */
    public function getResult(): Result
    {
        return $this->result;
    }
}
