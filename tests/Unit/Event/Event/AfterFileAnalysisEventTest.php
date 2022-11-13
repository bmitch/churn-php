<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Event\Event;

use Churn\Event\Event\AfterFileAnalysisEvent;
use Churn\File\File;
use Churn\Result\ResultInterface;
use Churn\Tests\BaseTestCase;
use Mockery as m;

class AfterFileAnalysisEventTest extends BaseTestCase
{
    /** @test */
    public function it_can_return_metrics(): void
    {
        $fullPath = '/tmp/file';
        $numberOfChanges = 2;
        $cyclomaticComplexity = 3;

        $file = new File($fullPath, $fullPath);
        $result = m::mock(ResultInterface::class);
        $result->shouldReceive('getFile')->andReturn($file);
        $result->shouldReceive('getCommits')->andReturn($numberOfChanges);
        $result->shouldReceive('getComplexity')->andReturn($cyclomaticComplexity);

        $event = new AfterFileAnalysisEvent($result);
        self::assertSame($result, $event->getResult());
        self::assertSame($fullPath, $event->getFilePath());
        self::assertSame($numberOfChanges, $event->getNumberOfChanges());
        self::assertSame($cyclomaticComplexity, $event->getCyclomaticComplexity());
    }
}
