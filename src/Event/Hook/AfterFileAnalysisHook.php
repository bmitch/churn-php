<?php

declare(strict_types=1);

namespace Churn\Event\Hook;

use Churn\Event\Event\AfterFileAnalysisEvent;

interface AfterFileAnalysisHook
{

    /**
     * @param AfterFileAnalysisEvent $event The event triggered when the analysis of a file is done.
     */
    public static function afterFileAnalysis(AfterFileAnalysisEvent $event): void;
}
