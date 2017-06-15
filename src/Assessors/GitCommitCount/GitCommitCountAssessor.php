<?php declare(strict_types = 1);

namespace Churn\Assessors\GitCommitCount;

use Churn\Services\CommandService;

class GitCommitCountAssessor
{
    /**
     * The command service.
     * @var CommandService
     */
    protected $commandService;

    /**
     * Class constructor.
     * @param CommandService $commandService Command Service.
     */
    public function __construct(CommandService $commandService)
    {
        $this->commandService = $commandService;
    }

    /**
     * See how many commits the file at $filePath has.
     * @param  string $filePath Path and filename.
     * @return integer
     */
    public function assess(string $filePath): int
    {
        $command = $this->buildCommand($filePath);

        $result = $this->commandService->execute($command);
        if (! isset($result[0])) {
            return 0;
        }
        $result = trim($result[0]);
        $explodedResult = explode(' ', $result);
        return (integer) $explodedResult[0];
    }

    /**
     * Build the command to get the number of commits for the file at $filePath.
     * @param  string $filePath Patha nd filename.
     * @return string
     */
    protected function buildCommand(string $filePath): string
    {
        $commandTemplate = "git log --name-only --pretty=format: %s | sort | uniq -c | sort -nr";

        $pos =strrpos($filePath, '/');
        if ($pos) {
            $folder = substr($filePath, 0, $pos);
            $commandTemplate = "cd {$folder} && git log --name-only --pretty=format: %s | sort | uniq -c | sort -nr";
        }

        return sprintf($commandTemplate, $filePath, $filePath);
    }
}
