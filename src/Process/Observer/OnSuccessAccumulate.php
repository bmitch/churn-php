<?php declare(strict_types = 1);

namespace Churn\Process\Observer;

use Churn\Result\Result;
use Churn\Result\ResultAccumulator;

class OnSuccessAccumulate implements OnSuccess
{
    /**
     * @var ResultAccumulator
     */
    private $accumulator;

    /**
     * Class constructor.
     * @param ResultAccumulator $accumulator The object accumulating the results.
     */
    public function __construct(ResultAccumulator $accumulator)
    {
        $this->accumulator = $accumulator;
    }

    /**
     * Triggers an event when a file is successfully processed.
     * @param Result $result The result for a file.
     * @return void
     */
    public function __invoke(Result $result): void
    {
        $this->accumulator->add($result);
    }
}
