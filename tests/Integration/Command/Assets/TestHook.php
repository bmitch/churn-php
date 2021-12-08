<?php

declare(strict_types=1);

namespace Churn\Tests\Integration\Command\Assets;

use Churn\Event\Event\AfterAnalysis as AfterAnalysisEvent;
use Churn\Event\Event\AfterFileAnalysis as AfterFileAnalysisEvent;
use Churn\Event\Event\BeforeAnalysis as BeforeAnalysisEvent;
use Churn\Event\Hook\AfterAnalysisHook;
use Churn\Event\Hook\AfterFileAnalysisHook;
use Churn\Event\Hook\BeforeAnalysisHook;

class TestHook implements AfterAnalysisHook, AfterFileAnalysisHook, BeforeAnalysisHook
{

    public static $nbAfterAnalysisEvent = 0;
    public static $nbAfterFileAnalysisEvent = 0;
    public static $nbBeforeAnalysisEvent = 0;

    public static function reset(): void
    {
        self::$nbAfterAnalysisEvent = 0;
        self::$nbAfterFileAnalysisEvent = 0;
        self::$nbBeforeAnalysisEvent = 0;
    }

    /**
     * @param AfterAnalysisEvent $event The event triggered when the analysis is done.
     */
    public static function afterAnalysis(AfterAnalysisEvent $event): void
    {
        self::$nbAfterAnalysisEvent++;
    }

    /**
     * @param AfterFileAnalysisEvent $event The event triggered when the analysis of a file is done.
     */
    public static function afterFileAnalysis(AfterFileAnalysisEvent $event): void
    {
        self::$nbAfterFileAnalysisEvent++;
    }

    /**
     * @param BeforeAnalysisEvent $event The event triggered when the analysis starts.
     */
    public static function beforeAnalysis(BeforeAnalysisEvent $event): void
    {
        self::$nbBeforeAnalysisEvent++;
    }
}
