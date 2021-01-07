<?php

declare(strict_types=1);

namespace Churn\Tests\Integration\Command;

use Churn\Command\RunCommand;
use Churn\Tests\BaseTestCase;
use DI\ContainerBuilder;
use InvalidArgumentException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class RunCommandTest extends BaseTestCase
{
    /** @var CommandTester */
    private $commandTester;

    /** @var string|null */
    private $tmpFile;

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

        if ($this->tmpFile !== null && \is_file($this->tmpFile)) {
            \unlink($this->tmpFile);
            $this->tmpFile = null;
        }
    }

    /** @test */
    public function it_displays_the_logo_at_the_beginning_by_default()
    {
        $exitCode = $this->commandTester->execute(['paths' => [realpath(__DIR__ . '/../..')]]);
        $display = $this->commandTester->getDisplay();

        $this->assertEquals(0, $exitCode);
        $this->assertEquals(RunCommand::LOGO, substr($display, 0, strlen(RunCommand::LOGO)));
    }

    /** @test */
    public function it_can_return_a_json_report()
    {
        $exitCode = $this->commandTester->execute(['paths' => [realpath(__DIR__ . '/../..')], '--format' => 'json']);
        $data = \json_decode($this->commandTester->getDisplay(), true);

        $this->assertEquals(0, $exitCode);
        $this->assertReport($data);
    }

    /** @test */
    public function it_can_write_a_json_report()
    {
        $this->tmpFile = \tempnam(\sys_get_temp_dir(), 'churn-test-');
        $exitCode = $this->commandTester->execute([
            'paths' => [realpath(__DIR__ . '/../..')],
            '--format' => 'json',
            '--output' => $this->tmpFile,
        ]);
        $display = $this->commandTester->getDisplay();

        $this->assertEquals(0, $exitCode);
        $this->assertEquals(RunCommand::LOGO, substr($display, 0, strlen(RunCommand::LOGO)));

        $this->assertFileExists($this->tmpFile);
        $data = \json_decode(\file_get_contents($this->tmpFile), true);
        $this->assertReport($data);
    }

    private function assertReport($data): void
    {
        $i = 0;
        foreach ($data as $key => $value) {
            $this->assertEquals($i++, $key);
            $this->assertArrayHasKey('file', $value);
            $this->assertArrayHasKey('commits', $value);
            $this->assertArrayHasKey('complexity', $value);
            $this->assertArrayHasKey('score', $value);
        }
    }

    /** @test */
    public function it_throws_for_invalid_configuration(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->commandTester->execute([
            'paths' => [realpath(__DIR__ . '/../..')],
            '--configuration' => 'not a valid configuration file',
        ]);
    }
}
