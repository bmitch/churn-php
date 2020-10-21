<?php declare(strict_types = 1);

namespace Churn\Tests\Unit\Process\Observer;

use Churn\Process\Observer\OnSuccess;
use Churn\Process\Observer\OnSuccessCollection;
use Churn\Tests\BaseTestCase;
use Churn\Result\Result;
use Mockery as m;

class OnSuccessCollectionTest extends BaseTestCase
{
    /** @test */
    public function it_invokes_other_observers()
    {
        $observer1 = m::mock(OnSuccess::class);
        $observer1->shouldReceive('__invoke')->once();
        $observer2 = m::mock(OnSuccess::class);
        $observer2->shouldReceive('__invoke')->once();
        $observerCollection = new OnSuccessCollection($observer1, $observer2);
        $observerCollection(m::mock(Result::class));
    }
}
