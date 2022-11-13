<?php

declare(strict_types=1);

namespace Churn\Tests\Integration\Command\Assets;

use Churn\Event\Event\AfterAnalysis;
use Churn\Event\Event\AfterFileAnalysis;
use Churn\Event\Event\BeforeAnalysis;
use Churn\Event\Hook\AfterAnalysisHook;
use Churn\Event\Hook\AfterFileAnalysisHook;
use Churn\Event\Hook\BeforeAnalysisHook;

class TestHook implements AfterAnalysisHook, AfterFileAnalysisHook, BeforeAnalysisHook
{

    /** @var int */
    public static $nbAfterAnalysisEvent = 0;
    /** @var int */
    public static $nbAfterFileAnalysisEvent = 0;
    /** @var int */
    public static $nbBeforeAnalysisEvent = 0;

    public static function reset(): void
    {
        self::$nbAfterAnalysisEvent = 0;
        self::$nbAfterFileAnalysisEvent = 0;
        self::$nbBeforeAnalysisEvent = 0;
    }

    /**
     * @param AfterAnalysis $event The event triggered when the analysis is done.
     */
    public static function afterAnalysis(AfterAnalysis $event): void
    {
        self::$nbAfterAnalysisEvent++;
    }

    /**
     * @param AfterFileAnalysis $event The event triggered when the analysis of a file is done.
     */
    public static function afterFileAnalysis(AfterFileAnalysis $event): void
    {
        self::$nbAfterFileAnalysisEvent++;
    }

    /**
     * @param BeforeAnalysis $event The event triggered when the analysis starts.
     */
    public static function beforeAnalysis(BeforeAnalysis $event): void
    {
        self::$nbBeforeAnalysisEvent++;
    }
}
