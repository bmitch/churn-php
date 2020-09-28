<?php declare(strict_types = 1);

namespace Churn\Tests\Unit\Renderers\Results;

use Churn\Results\Result;
use Mockery as m;
use Churn\Tests\BaseTestCase;
use Churn\Renderers\Results\JsonResultsRenderer;
use Churn\Results\ResultCollection;
use Symfony\Component\Console\Output\OutputInterface;

class JsonResultsRendererTest extends BaseTestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(JsonResultsRenderer::class, new JsonResultsRenderer);
    }

    /** @test */
    public function it_can_render_the_results_as_json()
    {
        $resultCollection = new ResultCollection([
            new Result(['file' => 'filename1.php', 'commits' => 5, 'complexity' => 7]),
            new Result(['file' => 'filename2.php', 'commits' => 3, 'complexity' => 4]),
            new Result(['file' => 'filename3.php', 'commits' => 1, 'complexity' => 5]),
            new Result(['file' => 'filename4.php', 'commits' => 1, 'complexity' => 1]),
            new Result(['file' => 'filename5.php', 'commits' => 8, 'complexity' => 1]),
        ]);

        $output = m::mock(OutputInterface::class);
        $output->shouldReceive('write')->atLeast()->once()->with(
            '[{"file":"filename1.php","commits":5,"complexity":7,"score":0.625},{"file":"filename2.php","commits":3,"complexity":4,"score":0.242},{"file":"filename3.php","commits":1,"complexity":5,"score":0.08},{"file":"filename4.php","commits":1,"complexity":1,"score":-0.225},{"file":"filename5.php","commits":8,"complexity":1,"score":0.143}]'
        );

        (new JsonResultsRenderer)->render($output, $resultCollection);
    }
}
