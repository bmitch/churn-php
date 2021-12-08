<?php

declare(strict_types=1);

namespace Churn\Event\Hook;

use Churn\Event\Event\BeforeAnalysis;

interface BeforeAnalysisHook
{

    /**
     * @param BeforeAnalysis $event The event triggered when the analysis starts.
     */
    public static function beforeAnalysis(BeforeAnalysis $event): void;
}
