<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Result;

use Churn\Result\ResultInterface;
use Churn\Result\HighestScores;
use Churn\Tests\BaseTestCase;
use Mockery as m;

class HighestScoresTest extends BaseTestCase
{
    /** @test */
    public function it_keeps_results_sorted_by_priority()
    {
        $scores = new HighestScores(10);
        $scores->add(m::mock(ResultInterface::class, ['getPriority' => 2]));
        $scores->add(m::mock(ResultInterface::class, ['getPriority' => 1]));
        $scores->add(m::mock(ResultInterface::class, ['getPriority' => 3]));

        $results = $scores->toArray();

        $this->assertCount(3, $results);
        $this->assertEquals(3, $results[0]->getPriority());
        $this->assertEquals(2, $results[1]->getPriority());
        $this->assertEquals(1, $results[2]->getPriority());
    }

    /** @test */
    public function it_keeps_ony_the_highest_priorities()
    {
        $scores = new HighestScores(3);
        $scores->add(m::mock(ResultInterface::class, ['getPriority' => 4]));
        $scores->add(m::mock(ResultInterface::class, ['getPriority' => 2]));
        $scores->add(m::mock(ResultInterface::class, ['getPriority' => 1]));
        $scores->add(m::mock(ResultInterface::class, ['getPriority' => 3]));
        $scores->add(m::mock(ResultInterface::class, ['getPriority' => 5]));

        $results = $scores->toArray();

        $this->assertCount(3, $results);
        $this->assertEquals(5, $results[0]->getPriority());
        $this->assertEquals(4, $results[1]->getPriority());
        $this->assertEquals(3, $results[2]->getPriority());
    }
}
