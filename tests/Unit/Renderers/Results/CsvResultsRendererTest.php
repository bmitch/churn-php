<?php

use Churn\Renderers\Results\CsvResultsRenderer;
use Churn\Results\Result;
use Mockery as m;
use Churn\Tests\BaseTestCase;
use Churn\Results\ResultCollection;
use Symfony\Component\Console\Output\OutputInterface;

class CsvResultsRendererTest extends BaseTestCase
{
    /** @test **/
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(CsvResultsRenderer::class, new CsvResultsRenderer);
    }

    /** @test **/
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
        $output->shouldReceive('writeln')->once()->with('"File";"Times Changed";"Complexity";"Score"');
        $output->shouldReceive('writeln')->once()->with('"filename1.php";5;7;0.625');
        $output->shouldReceive('writeln')->once()->with('"filename2.php";3;4;0.242');
        $output->shouldReceive('writeln')->once()->with('"filename3.php";1;5;0.08');
        $output->shouldReceive('writeln')->once()->with('"filename4.php";1;1;-0.225');
        $output->shouldReceive('writeln')->once()->with('"filename5.php";8;1;0.143');

        (new CsvResultsRenderer)->render($output, $resultCollection);
    }
}
