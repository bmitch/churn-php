<?php declare(strict_types = 1);

namespace Churn\Assessors\GitCommitCount;

use Churn\Services\CommandService;
use Churn\Exceptions\CommandServiceException;

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
     * @throws CommandServiceException If command resulted in an error.
     * @return integer
     */
    public function assess($filePath): int
    {
        $command = $this->buildCommand($filePath);
        $result = $this->commandService->execute($command);
        if (! isset($result[0])) {
            throw new CommandServiceException('Command resulted in an error (Does specified file exist?)');
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
    protected function buildCommand($filePath): string
    {
        $commandTemplate = "git log --name-only --pretty=format: %s | sort | uniq -c | sort -nr | grep %s";
        return sprintf($commandTemplate, $filePath, $filePath);
    }
}
