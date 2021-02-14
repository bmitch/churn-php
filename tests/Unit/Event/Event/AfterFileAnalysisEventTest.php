<?php

declare(strict_types=1);

namespace Churn\Tests\Event\Event;

use Churn\Event\Event\AfterFileAnalysisEvent;
use Churn\File\File;
use Churn\Result\Result;
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

        $file = m::mock(File::class);
        $file->shouldReceive('getFullPath')->andReturn($fullPath);
        $result = m::mock(Result::class);
        $result->shouldReceive('getFile')->andReturn($file);
        $result->shouldReceive('getCommits')->andReturn($numberOfChanges);
        $result->shouldReceive('getComplexity')->andReturn($cyclomaticComplexity);

        $event = new AfterFileAnalysisEvent($result);
        $this->assertEquals($result, $event->getResult());
        $this->assertEquals($fullPath, $event->getFilePath());
        $this->assertEquals($numberOfChanges, $event->getNumberOfChanges());
        $this->assertEquals($cyclomaticComplexity, $event->getCyclomaticComplexity());
    }
}
