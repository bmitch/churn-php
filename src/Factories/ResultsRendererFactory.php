<?php declare(strict_types = 1);

namespace Churn\Factories;

use Churn\Renderers\Results\ConsoleResultsRenderer;
use Churn\Renderers\Results\JsonResultsRenderer;
use Churn\Results\ResultCollection;
use InvalidArgumentException;
use Symfony\Component\Console\Output\OutputInterface;

class ResultsRendererFactory
{
    const FORMAT_JSON = 'json';
    const FORMAT_TEXT = 'text';

    /**
     * Render the results
     * @param string           $format  Format to render.
     * @param OutputInterface  $output  Output Interface.
     * @param ResultCollection $results Result Collection.
     * @throws InvalidArgumentException If output format invalid.
     * @return void
     */
    public function renderResults(string $format, OutputInterface $output, ResultCollection $results)
    {
        if ($format === self::FORMAT_JSON) {
            (new JsonResultsRenderer())->render($output, $results);
            return;
        }

        if ($format === self::FORMAT_TEXT) {
            (new ConsoleResultsRenderer())->render($output, $results);
            return;
        }

        throw new InvalidArgumentException('Invalid output format provided');
    }
}
