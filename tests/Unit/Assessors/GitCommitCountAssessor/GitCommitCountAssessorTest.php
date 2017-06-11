<?php declare(strict_types = 1);

namespace Churn\Tests\Assessors\CyclomaticComplexity;

use Mockery as m;
use Churn\Tests\BaseTestCase;
use Churn\Assessors\GitCommitCount\GitCommitCountAssessor;
use Churn\Services\CommandService;
use Churn\Exceptions\CommandServiceException;

class GitCommitCountAssessorTest extends BaseTestCase
{
    /** @test */
    public function when_a_file_has_one_commit_it_returns_one()
    {
        $commandService = m::mock(CommandService::class);
        $commandService->shouldReceive('execute')
            ->once()
            ->with("cd src/Assessors/CyclomaticComplexity && git log --name-only --pretty=format: src/Assessors/CyclomaticComplexity/CyclomaticComplexityAssessor.php | sort | uniq -c | sort -nr")
            ->andReturn(['      1 src/Assessors/CyclomaticComplexity/CyclomaticComplexityAssessor.php']);

        $this->assertSame(1, (new GitCommitCountAssessor($commandService))->assess('src/Assessors/CyclomaticComplexity/CyclomaticComplexityAssessor.php'));
    }

    /** @test */
    public function when_a_file_has_four_commits_it_returns_four()
    {
        $commandService = m::mock(CommandService::class);
        $commandService->shouldReceive('execute')
            ->once()
            ->with("git log --name-only --pretty=format: README.md | sort | uniq -c | sort -nr")
            ->andReturn(['      4 README.md']);

        $this->assertSame(4, (new GitCommitCountAssessor($commandService))->assess('README.md'));
    }

    /** @test */
    public function it_handles_the_scenario_when_the_provided_file_does_not_exist_or_has_no_commits()
    {
        $commandService = m::mock(CommandService::class);
        $commandService->shouldReceive('execute')
            ->once()
            ->with("git log --name-only --pretty=format: doesNotExist.md | sort | uniq -c | sort -nr")
            ->andReturn([]);

        $this->assertSame(0, (new GitCommitCountAssessor($commandService))->assess('doesNotExist.md'));
    }
}