<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Result\Render;

use Churn\Result\Render\MarkdownResultsRenderer;
use Churn\Tests\BaseTestCase;
use Mockery as m;
use Symfony\Component\Console\Output\OutputInterface;

class MarkdownResultsRendererTest extends BaseTestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(MarkdownResultsRenderer::class, new MarkdownResultsRenderer());
    }

    /** @test */
    public function it_can_render_the_results_as_markdown()
    {
        $results = [
            ['filename1.php', 5, 7, 0.625],
            ['path/filename2.php', 3, 4, 0.242],
            ['pa|th/filename3.php', 1, 5, 0.08],
        ];

        $output = m::mock(OutputInterface::class);
        $output->shouldReceive('writeln')->once()->with('| File | Times Changed | Complexity | Score |');
        $output->shouldReceive('writeln')->once()->with('|------|---------------|------------|-------|');
        $output->shouldReceive('writeln')->once()->with('| filename1.php | 5 | 7 | 0.625 |');
        $output->shouldReceive('writeln')->once()->with('| path/filename2.php | 3 | 4 | 0.242 |');
        $output->shouldReceive('writeln')->once()->with('| pa\\|th/filename3.php | 1 | 5 | 0.08 |');

        (new MarkdownResultsRenderer())->render($output, $results);
    }
}
