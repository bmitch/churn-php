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

    /** @return void */
    public function setUp()
    {
        parent::setUp();

        $this->factory = new ResultsRendererFactory();
    }

    /** @test */
    public function it_returns_the_json_renderer_when_provided_json_format(): void
    {
        self::assertInstanceOf(JsonResultsRenderer::class, $this->factory->getRenderer('json'));
    }

    /** @test */
    public function it_returns_the_csv_renderer_when_provided_csv_format(): void
    {
        self::assertInstanceOf(CsvResultsRenderer::class, $this->factory->getRenderer('csv'));
    }

    /** @test */
    public function it_returns_the_markdown_renderer_when_provided_markdown_format(): void
    {
        self::assertInstanceOf(MarkdownResultsRenderer::class, $this->factory->getRenderer('markdown'));
    }

    /** @test */
    public function it_returns_the_console_renderer_when_provided_text_format(): void
    {
        $factory = new ResultsRendererFactory();
        self::assertInstanceOf(ConsoleResultsRenderer::class, $this->factory->getRenderer('text'));
    }

    /** @test */
    public function it_throws_exception_if_format_is_invalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid output format provided');
        $this->factory->getRenderer('foobar');
    }
}
