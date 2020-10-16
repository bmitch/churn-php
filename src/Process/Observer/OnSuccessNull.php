<?php declare(strict_types = 1);

namespace Churn\Process\Observer;

use Churn\Values\File;

class OnSuccessNull implements OnSuccess
{
    /**
     * Do nothing.
     * @param File $file The file successfully processed.
     * @return void
     */
    public function __invoke(File $file): void
    {
    }
}
