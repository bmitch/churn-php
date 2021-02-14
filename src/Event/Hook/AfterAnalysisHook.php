<?php

declare(strict_types=1);

namespace Churn\Event\Hook;

use Churn\Event\Event\AfterAnalysisEvent;

interface AfterAnalysisHook
{

    /**
     * @param AfterAnalysisEvent $event The event triggered when the analysis is done.
     */
    public static function afterAnalysis(AfterAnalysisEvent $event): void;
}
