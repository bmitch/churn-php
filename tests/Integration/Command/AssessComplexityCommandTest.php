<?php

declare(strict_types=1);

namespace Churn\Tests\Integration\Command;

use Churn\Command\AssessComplexityCommand;
use Churn\Tests\BaseTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class AssessComplexityCommandTest extends BaseTestCase
{
    /** @var CommandTester */
    private $commandTester;

    protected function setUp()
    {
        $application = new Application('churn-php', 'test');
        $application->add(AssessComplexityCommand::newInstance());
        $command = $application->find('assess-complexity');
        $this->commandTester = new CommandTester($command);
    }

    protected function tearDown()
    {
        $this->commandTester = null;
    }

    /** @test */
    public function it_returns_the_cyclomatic_complexity_greater_than_zero()
    {
        $exitCode = $this->commandTester->execute(['file' => __FILE__]);
        $result = \rtrim($this->commandTester->getDisplay());

        $this->assertEquals(0, $exitCode);
        $this->assertTrue(\ctype_digit($result), 'The result of the command must be an integer');
        $this->assertGreaterThan(0, (int) $result);
    }

    /** @test */
    public function it_returns_zero_for_non_existing_file()
    {
        $exitCode = $this->commandTester->execute(['file' => 'nonexisting-file.php']);
        $result = \rtrim($this->commandTester->getDisplay());

        $this->assertEquals(0, $exitCode);
        $this->assertTrue(\ctype_digit($result), 'The result of the command must be an integer');
        $this->assertEquals(0, (int) $result);
    }
}
