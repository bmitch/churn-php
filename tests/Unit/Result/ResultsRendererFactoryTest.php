<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Result;

use Churn\Result\Render\ConsoleResultsRenderer;
use Churn\Result\Render\CsvResultsRenderer;
use Churn\Result\Render\JsonResultsRenderer;
use Churn\Result\Render\MarkdownResultsRenderer;
use Churn\Result\ResultsRendererFactory;
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

    /** @test */
    public function it_returns_the_json_renderer_when_provided_json_format()
    {
        $this->assertInstanceOf(JsonResultsRenderer::class, $this->factory->getRenderer('json'));
    }

    /** @test */
    public function it_returns_the_csv_renderer_when_provided_csv_format()
    {
        $this->assertInstanceOf(CsvResultsRenderer::class, $this->factory->getRenderer('csv'));
    }

    /** @test */
    public function it_returns_the_markdown_renderer_when_provided_markdown_format()
    {
        $this->assertInstanceOf(MarkdownResultsRenderer::class, $this->factory->getRenderer('markdown'));
    }

    /** @test */
    public function it_returns_the_console_renderer_when_provided_text_format()
    {
        $factory = new ResultsRendererFactory();
        $this->assertInstanceOf(ConsoleResultsRenderer::class, $this->factory->getRenderer('text'));
    }

    /** @test */
    public function it_throws_exception_if_format_is_invalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid output format provided');
        $this->factory->getRenderer('foobar');
    }

    public function setUp()
    {
        $this->factory = new ResultsRendererFactory();
    }
}
