<?php

declare(strict_types=1);

namespace Churn\Event\Subscriber;

use Churn\Event\Event\BeforeAnalysis as BeforeAnalysisEvent;

/**
 * @internal
 */
interface BeforeAnalysis
{
    /**
     * @param BeforeAnalysisEvent $event The event triggered when the analysis starts.
     */
    public function onBeforeAnalysis(BeforeAnalysisEvent $event): void;
}
