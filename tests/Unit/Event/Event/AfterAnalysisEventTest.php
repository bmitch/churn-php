<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Event\Event;

use Churn\Event\Event\AfterAnalysisEvent;
use Churn\Result\ResultReporter;
use Churn\Tests\BaseTestCase;
use Mockery as m;

final class AfterAnalysisEventTest extends BaseTestCase
{
    /** @test */
    public function it_can_return_metrics(): void
    {
        $numberOfFiles = 1;
        $maxNumberOfChanges = 2;
        $maxCyclomaticComplexity = 3;
        $maxScore = 0.5;

        $report = m::mock(ResultReporter::class);
        $report->shouldReceive('getNumberOfFiles')->andReturn($numberOfFiles);
        $report->shouldReceive('getMaxCommits')->andReturn($maxNumberOfChanges);
        $report->shouldReceive('getMaxComplexity')->andReturn($maxCyclomaticComplexity);
        $report->shouldReceive('getMaxScore')->andReturn($maxScore);

        $event = new AfterAnalysisEvent($report);
        self::assertSame($numberOfFiles, $event->getNumberOfFiles());
        self::assertSame($maxNumberOfChanges, $event->getMaxNumberOfChanges());
        self::assertSame($maxCyclomaticComplexity, $event->getMaxCyclomaticComplexity());
        self::assertSame($maxScore, $event->getMaxScore());
    }
}
