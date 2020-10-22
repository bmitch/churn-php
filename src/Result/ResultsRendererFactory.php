<?php declare(strict_types = 1);

namespace Churn\Result;

use Churn\Result\Render\ConsoleResultsRenderer;
use Churn\Result\Render\CsvResultsRenderer;
use Churn\Result\Render\JsonResultsRenderer;
use Churn\Result\Render\ResultsRendererInterface;
use InvalidArgumentException;

class ResultsRendererFactory
{
    const FORMAT_JSON = 'json';
    const FORMAT_CSV = 'csv';
    const FORMAT_TEXT = 'text';

    /**
     * Render the results
     * @param string $format Format to render.
     * @throws InvalidArgumentException If output format invalid.
     * @return ResultsRendererInterface
     */
    public function getRenderer(string $format): ResultsRendererInterface
    {
        if ($format === self::FORMAT_CSV) {
            return new CsvResultsRenderer;
        }

        if ($format === self::FORMAT_JSON) {
            return new JsonResultsRenderer;
        }

        if ($format === self::FORMAT_TEXT) {
            return new ConsoleResultsRenderer;
        }

        throw new InvalidArgumentException('Invalid output format provided');
    }
}
