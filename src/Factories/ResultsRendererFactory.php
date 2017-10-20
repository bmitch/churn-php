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
     * @var JsonResultsRenderer
     */
    protected $jsonRenderer;

    /**
     * @var ConsoleResultsRenderer
     */
    protected $consoleRenderer;

    /**
     * @param JsonResultsRenderer    $jsonRenderer
     * @param ConsoleResultsRenderer $consoleRenderer
     */
    public function __construct(
        JsonResultsRenderer $jsonRenderer,
        ConsoleResultsRenderer $consoleRenderer
    ) {
        $this->jsonRenderer = $jsonRenderer;
        $this->consoleRenderer = $consoleRenderer;
    }

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
            $this->jsonRenderer->render($output, $results);
            return;
        }

        if ($format === self::FORMAT_TEXT) {
            $this->consoleRenderer->render($output, $results);
            return;
        }

        throw new InvalidArgumentException('Invalid output format provided');
    }
}
