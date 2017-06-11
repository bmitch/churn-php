<?php declare(strict_types = 1);

namespace Churn\Services;

class CommandService
{
    /**
     * Run a command line command and return the result.
     * @param  string $command Command to execute.
     * @return array
     */
    public function execute($command): array
    {
        exec($command, $output);
        return $output;
    }
}
