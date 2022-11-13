<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Result\Render;

use Churn\Result\Render\JsonResultsRenderer;
use Churn\Tests\BaseTestCase;
use Mockery as m;
use Symfony\Component\Console\Output\OutputInterface;

class JsonResultsRendererTest extends BaseTestCase
{
    /** @test */
    public function it_can_render_the_results_as_json(): void
    {
        $results = [
            ['filename1.php', 5, 7, 0.625],
            ['filename2.php', 3, 4, 0.242],
            ['filename3.php', 1, 5, 0.08],
            ['filename4.php', 1, 1, -0.225],
            ['filename5.php', 8, 1, 0.143],
        ];

        $output = m::mock(OutputInterface::class);
        $output->shouldReceive('write')->atLeast()->once()->with(
            '[{"file":"filename1.php","commits":5,"complexity":7,"score":0.625},{"file":"filename2.php","commits":3,"complexity":4,"score":0.242},{"file":"filename3.php","commits":1,"complexity":5,"score":0.08},{"file":"filename4.php","commits":1,"complexity":1,"score":-0.225},{"file":"filename5.php","commits":8,"complexity":1,"score":0.143}]'
        );

        (new JsonResultsRenderer())->render($output, $results);
    }
}
