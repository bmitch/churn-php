<?php

declare(strict_types=1);

namespace Churn\Tests\Integration\Command\Assets;

use Churn\Event\Event\AfterAnalysisEvent;
use Churn\Event\Event\BeforeAnalysisEvent;
use Churn\Event\Hook\AfterAnalysisHook;
use Churn\Event\Hook\BeforeAnalysisHook;

class PrintHook implements AfterAnalysisHook, BeforeAnalysisHook
{

    /**
     * @param AfterAnalysisEvent $event The event triggered when the analysis is done.
     */
    public static function afterAnalysis(AfterAnalysisEvent $event): void
    {
        echo "DONE";
    }

    /**
     * @param BeforeAnalysisEvent $event The event triggered when the analysis starts.
     */
    public static function beforeAnalysis(BeforeAnalysisEvent $event): void
    {
        echo "Churn: ";
    }
}
