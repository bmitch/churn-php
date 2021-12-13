<?php

declare(strict_types=1);

namespace Churn\Event\Hook;

use Churn\Event\Event\AfterAnalysis;

interface AfterAnalysisHook
{
    /**
     * @param AfterAnalysis $event The event triggered when the analysis is done.
     */
    public static function afterAnalysis(AfterAnalysis $event): void;
}
