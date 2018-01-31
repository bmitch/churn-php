<?php declare(strict_types = 1);

namespace Churn\Factories;

use Churn\Renderers\Results\ConsoleResultsRenderer;
use Churn\Renderers\Results\CsvResultsRenderer;
use Churn\Renderers\Results\JsonResultsRenderer;
use Churn\Renderers\Results\ResultsRendererInterface;
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
