<?php

declare(strict_types=1);

namespace Churn\Event\Hook;

use Churn\Event\Event\AfterFileAnalysis;

interface AfterFileAnalysisHook
{
    /**
     * @param AfterFileAnalysis $event The event triggered when the analysis of a file is done.
     */
    public static function afterFileAnalysis(AfterFileAnalysis $event): void;
}
