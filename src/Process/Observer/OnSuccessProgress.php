<?php declare(strict_types = 1);

namespace Churn\Process\Observer;

use Churn\Result\Result;
use Symfony\Component\Console\Helper\ProgressBar;

class OnSuccessProgress implements OnSuccess
{

    /**
     * @var ProgressBar The progress bar to advance.
     */
    private $progressBar;

    /**
     * OnSuccessProgress constructor.
     *
     * @param ProgressBar $progressBar The progress bar to advance.
     */
    public function __construct(ProgressBar $progressBar)
    {
        $this->progressBar = $progressBar;
    }

    /**
     * Triggers an event when a file is successfully processed.
     *
     * @param Result $result The result for a file.
     */
    public function __invoke(Result $result): void
    {
        $this->progressBar->advance();
    }
}
