<?php declare(strict_types = 1);

namespace Churn\Process\Observer;

use Churn\Result\Result;

class OnSuccessCollection implements OnSuccess
{

    /**
     * @var array<OnSuccess>
     */
    private $observers;

    /**
     * Class constructor.
     *
     * @param OnSuccess ...$observers List of observers.
     */
    public function __construct(OnSuccess ...$observers)
    {
        $this->observers = $observers;
    }

    /**
     * Triggers an event when a file is successfully processed.
     *
     * @param Result $result The result for a file.
     */
    public function __invoke(Result $result): void
    {
        foreach ($this->observers as $observer) {
            $observer($result);
        }
    }
}
