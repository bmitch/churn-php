<?php

declare(strict_types=1);

namespace Churn\Result\Render;

use Symfony\Component\Console\Output\OutputInterface;

class JsonResultsRenderer implements ResultsRendererInterface
{

    /**
     * Renders the results.
     *
     * @param OutputInterface $output Output Interface.
     * @param array<array<float|integer|string>> $results The results.
     */
    public function render(OutputInterface $output, array $results): void
    {
        $data = \array_map(static function (array $result): array {
            return [
                'file' => $result[0],
                'commits' => $result[1],
                'complexity' => $result[2],
                'score' => $result[3],
            ];
        }, $results);

        $output->write(\json_encode($data));
    }
}
