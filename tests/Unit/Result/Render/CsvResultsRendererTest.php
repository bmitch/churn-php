<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Result\Render;

use Churn\Result\Render\CsvResultsRenderer;
use Churn\Tests\BaseTestCase;
use Mockery as m;
use Symfony\Component\Console\Output\OutputInterface;

class CsvResultsRendererTest extends BaseTestCase
{
    /** @test */
    public function it_can_render_the_results_as_csv(): void
    {
        $results = [
            ['filename1.php', 5, 7, 0.625],
            ['filename2.php', 3, 4, 0.242],
            ['filename3.php', 1, 5, 0.08],
            ['filename4.php', 1, 1, -0.225],
            ['filename5.php', 8, 1, 0.143],
        ];

        $output = m::mock(OutputInterface::class);
        $output->shouldReceive('writeln')->once()->with('"File";"Times Changed";"Complexity";"Score"');
        $output->shouldReceive('writeln')->once()->with('"filename1.php";5;7;0.625');
        $output->shouldReceive('writeln')->once()->with('"filename2.php";3;4;0.242');
        $output->shouldReceive('writeln')->once()->with('"filename3.php";1;5;0.08');
        $output->shouldReceive('writeln')->once()->with('"filename4.php";1;1;-0.225');
        $output->shouldReceive('writeln')->once()->with('"filename5.php";8;1;0.143');

        (new CsvResultsRenderer())->render($output, $results);
    }
}
