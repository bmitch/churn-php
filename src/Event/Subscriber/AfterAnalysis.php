<?php

declare(strict_types=1);

namespace Churn\Event\Subscriber;

use Churn\Event\Event\AfterAnalysis as AfterAnalysisEvent;

/**
 * @internal
 */
interface AfterAnalysis
{
    /**
     * @param AfterAnalysisEvent $event The event triggered when the analysis is done.
     */
    public function onAfterAnalysis(AfterAnalysisEvent $event): void;
}
