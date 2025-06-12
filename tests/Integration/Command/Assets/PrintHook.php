<?php

declare(strict_types=1);

namespace Churn\Tests\Integration\Command\Assets;

use Churn\Event\Event\AfterAnalysis;
use Churn\Event\Event\BeforeAnalysis;
use Churn\Event\Hook\AfterAnalysisHook;
use Churn\Event\Hook\BeforeAnalysisHook;

class PrintHook implements AfterAnalysisHook, BeforeAnalysisHook
{

    /**
     * @param AfterAnalysis $event The event triggered when the analysis is done.
     */
    #[\Override]
    public static function afterAnalysis(AfterAnalysis $event): void
    {
        echo "DONE";
    }

    /**
     * @param BeforeAnalysis $event The event triggered when the analysis starts.
     */
    #[\Override]
    public static function beforeAnalysis(BeforeAnalysis $event): void
    {
        echo "Churn: ";
    }
}
