<?php declare(strict_types = 1);

namespace Churn\Tests\Integration\Command;

use Churn\Command\RunCommand;
use Churn\Tests\BaseTestCase;
use DI\ContainerBuilder;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class RunCommandTest extends BaseTestCase
{
    /** @var CommandTester */
    private $commandTester;

    protected function setUp()
    {
        $container = ContainerBuilder::buildDevContainer();
        $application = new Application('churn-php', 'test');
        $application->add($container->get(RunCommand::class));
        $command = $application->find('run');
        $this->commandTester = new CommandTester($command);
    }
 
    protected function tearDown()
    {
        $this->commandTester = null;
    }

    /** @test */
    public function it_displays_the_logo_at_the_beginning_by_default()
    {
        $exitCode = $this->commandTester->execute(['paths' => [realpath(__DIR__ . '/../..')]]);
        $display = $this->commandTester->getDisplay();

        $this->assertEquals(0, $exitCode);
        $this->assertEquals(RunCommand::LOGO, substr($display, 0, strlen(RunCommand::LOGO)));
    }
}
