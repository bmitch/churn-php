<?php declare(strict_types = 1);

namespace Churn\Tests\Unit\Process\Observer;

use Churn\Process\Observer\OnSuccessAccumulate;
use Churn\Tests\BaseTestCase;
use Churn\Result\Result;
use Churn\Result\ResultAccumulator;
use Mockery as m;

class OnSuccessAccumulateTest extends BaseTestCase
{
    /** @test */
    public function it_accumulates_the_results()
    {
        $result1 = m::mock(Result::class);
        $result2 = m::mock(Result::class);
        $accumultor = m::mock(ResultAccumulator::class);
        $accumultor->shouldReceive('add')->twice();
        $observer = new OnSuccessAccumulate($accumultor);
        $observer($result1);
        $observer($result2);
    }
}
