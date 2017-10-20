<?php
/**
 * Created by PhpStorm.
 * User: rokas
 * Date: 17.10.20
 * Time: 23.06
 */

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
     * @var JsonResultsRenderer|\PHPUnit_Framework_MockObject_MockObject
     */
    private $jsonResultsRenderer;

    /**
     * @var ConsoleResultsRenderer|\PHPUnit_Framework_MockObject_MockObject
     */
    private $consoleResultsRenderer;

    /**
     * @var ResultsRendererFactory
     */
    private $resultsRendererFactory;

    public function setUp()
    {
        $this->jsonResultsRenderer = $this->createMock(JsonResultsRenderer::class);
        $this->consoleResultsRenderer = $this->createMock(ConsoleResultsRenderer::class);

        $this->resultsRendererFactory = new ResultsRendererFactory(
            $this->jsonResultsRenderer,
            $this->consoleResultsRenderer
        );
    }

    /**
     * @test
     */
    public function it_can_be_created()
    {
        $this->assertInstanceOf(ResultsRendererFactory::class, $this->resultsRendererFactory);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @dataProvider invalid_format_provider
     * @param string $format
     */
    public function it_throws_exception_if_format_is_invalid(string $format)
    {
        $outputMock = $this->createMock(OutputInterface::class);
        $resultsMock = $this->createMock(ResultCollection::class);

        $this->resultsRendererFactory->renderResults($format, $outputMock, $resultsMock);
    }

    /**
     * @return \Generator
     */
    public function invalid_format_provider()
    {
        yield ['test'];
        yield ['invalid'];
        yield [''];
    }

    /**
     * @test
     */
    public function it_can_render_json_format()
    {
        $outputMock = $this->createMock(OutputInterface::class);
        $resultsMock = $this->createMock(ResultCollection::class);

        $this->jsonResultsRenderer->expects($this->once())
            ->method('render')
            ->with($outputMock, $resultsMock);

        $this->consoleResultsRenderer->expects($this->never())
            ->method('render');

        $this->resultsRendererFactory->renderResults(ResultsRendererFactory::FORMAT_JSON, $outputMock, $resultsMock);
    }

    /**
     * @test
     */
    public function it_can_render_text_format()
    {
        $outputMock = $this->createMock(OutputInterface::class);
        $resultsMock = $this->createMock(ResultCollection::class);

        $this->jsonResultsRenderer->expects($this->never())
            ->method('render');

        $this->consoleResultsRenderer->expects($this->once())
            ->method('render')
            ->with($outputMock, $resultsMock);

        $this->resultsRendererFactory->renderResults(ResultsRendererFactory::FORMAT_TEXT, $outputMock, $resultsMock);
    }
}
