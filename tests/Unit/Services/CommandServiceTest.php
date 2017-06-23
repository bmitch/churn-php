<?php declare(strict_types = 1);

namespace Churn\Tests\Results;

use Churn\Tests\BaseTestCase;
use Churn\Services\CommandService;

class CommandServiceTest extends BaseTestCase
{
    /** @test */
    public function it_returns_the_output_of_a_command()
    {
        $commandService = new CommandService;
        $result = $commandService->execute("echo hi");
        $this->assertSame(["hi"], $result);
    }
}