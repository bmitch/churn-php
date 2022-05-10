<?php

declare(strict_types=1);

namespace Churn\Result;

use Churn\Result\Render\ConsoleResultsRenderer;
use Churn\Result\Render\CsvResultsRenderer;
use Churn\Result\Render\JsonResultsRenderer;
use Churn\Result\Render\MarkdownResultsRenderer;
use Churn\Result\Render\ResultsRendererInterface;
use InvalidArgumentException;

/**
 * @internal
 */
final class ResultsRendererFactory
{
    private const FORMAT_CSV = 'csv';
    private const FORMAT_JSON = 'json';
    private const FORMAT_MD = 'markdown';
    private const FORMAT_TEXT = 'text';

    /**
     * Render the results
     *
     * @param string $format Format to render.
     * @throws InvalidArgumentException If output format invalid.
     */
    public function getRenderer(string $format): ResultsRendererInterface
    {
        if (self::FORMAT_CSV === $format) {
            return new CsvResultsRenderer();
        }

        if (self::FORMAT_JSON === $format) {
            return new JsonResultsRenderer();
        }

        if (self::FORMAT_MD === $format) {
            return new MarkdownResultsRenderer();
        }

        if (self::FORMAT_TEXT === $format) {
            return new ConsoleResultsRenderer();
        }

        throw new InvalidArgumentException('Invalid output format provided');
    }
}
