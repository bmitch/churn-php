<?php

declare(strict_types=1);

namespace Churn\Process\Observer;

use Churn\Process\CacheProcessFactory;
use Churn\Result\Result;

class OnSuccessCache implements OnSuccess
{

    /**
     * @var CacheProcessFactory
     */
    private $cacheProcessFactory;

    /**
     * Class constructor.
     *
     * @param CacheProcessFactory $cacheProcessFactory The object writing the cache.
     */
    public function __construct(CacheProcessFactory $cacheProcessFactory)
    {
        $this->cacheProcessFactory = $cacheProcessFactory;
    }

    /**
     * Triggers an event when a file is successfully processed.
     *
     * @param Result $result The result for a file.
     */
    public function __invoke(Result $result): void
    {
        $this->cacheProcessFactory->addToCache($result);
    }
}
