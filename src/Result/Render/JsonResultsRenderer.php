<?php

declare(strict_types=1);

namespace Churn\Result\Render;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 */
final class JsonResultsRenderer implements ResultsRendererInterface
{
    /**
     * Renders the results.
     *
     * @param OutputInterface $output Output Interface.
     * @param array<array<float|integer|string>> $results The results.
     */
    public function render(OutputInterface $output, array $results): void
    {
        $data = [];

        foreach ($results as $result) {
            $data[] = [
                'commits' => $result[1],
                'complexity' => $result[2],
                'file' => $result[0],
                'score' => $result[3],
            ];
        }

        $output->write((string) \json_encode($data));
    }
}
