<?php

declare(strict_types=1);

namespace Churn\Tests\Integration\Command\Assets;

use Churn\Event\Event\AfterAnalysis as AfterAnalysisEvent;
use Churn\Event\Event\AfterFileAnalysis as AfterFileAnalysisEvent;
use Churn\Event\Event\BeforeAnalysis as BeforeAnalysisEvent;
use Churn\Event\Hook\AfterAnalysisHook;
use Churn\Event\Hook\AfterFileAnalysisHook;
use Churn\Event\Hook\BeforeAnalysisHook;

class TestAfterAnalysisHook implements AfterAnalysisHook
{
    public static $nbAfterAnalysisEvent = 0;

    /**
     * @param AfterAnalysisEvent $event The event triggered when the analysis is done.
     */
    public static function afterAnalysis(AfterAnalysisEvent $event): void
    {
        self::$nbAfterAnalysisEvent++;
    }
}

class TestAfterFileAnalysisHook implements AfterFileAnalysisHook
{
    public static $nbAfterFileAnalysisEvent = 0;

    /**
     * @param AfterFileAnalysisEvent $event The event triggered when the analysis of a file is done.
     */
    public static function afterFileAnalysis(AfterFileAnalysisEvent $event): void
    {
        self::$nbAfterFileAnalysisEvent++;
    }
}

class TestBeforeAnalysisHook implements BeforeAnalysisHook
{
    public static $nbBeforeAnalysisEvent = 0;

    /**
     * @param BeforeAnalysisEvent $event The event triggered when the analysis starts.
     */
    public static function beforeAnalysis(BeforeAnalysisEvent $event): void
    {
        self::$nbBeforeAnalysisEvent++;
    }
}
