<?php

declare(strict_types=1);

namespace Churn\Event\Hook;

use Churn\Event\Event\BeforeAnalysisEvent;

interface BeforeAnalysisHook
{

    /**
     * @param BeforeAnalysisEvent $event The event triggered when the analysis starts.
     */
    public static function beforeAnalysis(BeforeAnalysisEvent $event): void;
}
