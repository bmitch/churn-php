<?php

namespace Churn\Tests\Results;

use Churn\Factories\ResultsRendererFactory;
use Churn\Renderers\Results\ConsoleResultsRenderer;
use Churn\Renderers\Results\JsonResultsRenderer;
use Churn\Results\ResultCollection;
use Churn\Tests\BaseTestCase;
use Symfony\Component\Console\Output\OutputInterface;

class ResultsRendererFactoryTest extends BaseTestCase
{
    /**
     * @var ResultsRendererFactory
     */
    private $resultsRendererFactory;

    public function setUp()
    {
        $this->resultsRendererFactory = new ResultsRendererFactory();
    }

    /** @test */
    public function it_can_be_created()
    {
        $this->assertInstanceOf(ResultsRendererFactory::class, $this->resultsRendererFactory);
    }

    /** @test */
    public function it_throws_exception_if_format_is_invalid()
    {
        $this->expectException(\InvalidArgumentException::class);

        $outputMock = $this->createMock(OutputInterface::class);
        $resultsMock = $this->createMock(ResultCollection::class);

        $this->resultsRendererFactory->renderResults('invalid', $outputMock, $resultsMock);
    }

    /** @test */
    public function it_can_render_json_format()
    {
        $results = [
            [
                0 => 'file_1',
                1 => 'commits_1',
                2 => 'complexity_1',
                3 => 'score_1',
            ],
            [
                0 => 'file_2',
                1 => 'commits_2',
                2 => 'complexity_2',
                3 => 'score_2',
            ],
        ];
        $resultsMock = $this->createMock(ResultCollection::class);
        $resultsMock->method('toArray')->willReturn($results);

        $expectedOutputData = json_encode([
            [
                'file' => 'file_1',
                'commits' => 'commits_1',
                'complexity' => 'complexity_1',
                'score' => 'score_1',
            ],
            [
                'file' => 'file_2',
                'commits' => 'commits_2',
                'complexity' => 'complexity_2',
                'score' => 'score_2',
            ]
        ]);
        $outputMock = $this->createMock(OutputInterface::class);
        $outputMock->expects($this->atLeastOnce())->method('write')->with($expectedOutputData);

        $this->resultsRendererFactory->renderResults(ResultsRendererFactory::FORMAT_JSON, $outputMock, $resultsMock);
    }
}
