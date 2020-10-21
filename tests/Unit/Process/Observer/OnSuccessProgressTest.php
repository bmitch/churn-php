<?php declare(strict_types = 1);

namespace Churn\Tests\Unit\Process\Observer;

use Churn\Process\Observer\OnSuccessProgress;
use Churn\Tests\BaseTestCase;
use Churn\Result\Result;
use Mockery as m;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\NullOutput;

class OnSuccessProgressTest extends BaseTestCase
{
    /** @test */
    public function it_advances_the_progress_bar()
    {
        $progressBar = new ProgressBar(new NullOutput());
        $observer = new OnSuccessProgress($progressBar);
        $observer(m::mock(Result::class));
        $this->assertEquals(1, $progressBar->getProgress());
    }
}
