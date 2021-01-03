<?php

declare(strict_types=1);

namespace Churn\Tests\Integration\Command;

use Churn\Command\AssessComplexityCommand;
use Churn\Tests\BaseTestCase;
use DI\ContainerBuilder;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class AssessComplexityCommandTest extends BaseTestCase
{
    /** @var CommandTester */
    private $commandTester;

    protected function setUp()
    {
        $container = ContainerBuilder::buildDevContainer();
        $application = new Application('churn-php', 'test');
        $application->add($container->get(AssessComplexityCommand::class));
        $command = $application->find('assess-complexity');
        $this->commandTester = new CommandTester($command);
    }

    protected function tearDown()
    {
        $this->commandTester = null;
    }

    /** @test */
    public function it_returns_the_cyclomatic_complexity()
    {
        $exitCode = $this->commandTester->execute(['file' => __FILE__]);
        $result = \rtrim($this->commandTester->getDisplay());

        $this->assertEquals(0, $exitCode);
        $this->assertTrue(\ctype_digit($result), 'The result of the command must be an integer');
        $this->assertGreaterThan(0, (int) $result);
    }
}
