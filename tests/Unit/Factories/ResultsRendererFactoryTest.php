<?php

namespace Churn\Tests\Results;

use Churn\Factories\ResultsRendererFactory;
use Churn\Renderers\Results\ConsoleResultsRenderer;
use Churn\Renderers\Results\CsvResultsRenderer;
use Churn\Renderers\Results\JsonResultsRenderer;
use Churn\Tests\BaseTestCase;
use InvalidArgumentException;

class ResultsRendererFactoryTest extends BaseTestCase
{
    /**
     * @var ResultsRendererFactory
     */
    private $factory;

    /** @test */
    public function it_can_be_created()
    {
        $this->assertInstanceOf(ResultsRendererFactory::class, $this->factory);
    }

    /** @test **/
    public function it_returns_the_json_renderer_when_provided_json_format()
    {
        $this->assertInstanceOf(JsonResultsRenderer::class, $this->factory->getRenderer('json'));
    }

    /** @test **/
    public function it_returns_the_csv_renderer_when_provided_csv_format()
    {
        $this->assertInstanceOf(CsvResultsRenderer::class, $this->factory->getRenderer('csv'));
    }

    /** @test **/
    public function it_returns_the_console_renderer_when_provided_text_format()
    {
        $factory = new ResultsRendererFactory();
        $this->assertInstanceOf(ConsoleResultsRenderer::class, $this->factory->getRenderer('text'));
    }

    /** @test */
    public function it_throws_exception_if_format_is_invalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->assertInstanceOf(ConsoleResultsRenderer::class, $this->factory->getRenderer('foobar'));
    }

    public function setUp()
    {
        $this->factory = new ResultsRendererFactory();
    }
}
