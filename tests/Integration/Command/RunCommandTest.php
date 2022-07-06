<?php

declare(strict_types=1);

namespace Churn\Tests\Integration\Command;

use Churn\Command\RunCommand;
use Churn\Tests\BaseTestCase;
use Churn\Tests\Integration\Command\Assets\PrintHook;
use Churn\Tests\Integration\Command\Assets\TestAfterAnalysisHook;
use Churn\Tests\Integration\Command\Assets\TestAfterFileAnalysisHook;
use Churn\Tests\Integration\Command\Assets\TestBeforeAnalysisHook;
use Churn\Tests\Integration\Command\Assets\TestHook;
use InvalidArgumentException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class RunCommandTest extends BaseTestCase
{

    private const BAR = '0 [>---------------------------]';

    /** @var CommandTester */
    private $commandTester;

    /** @var string|null */
    private $tmpFile;

    protected function setUp()
    {
        $application = new Application('churn-php', 'test');
        $application->add(RunCommand::newInstance());
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
        $exitCode = $this->commandTester->execute([
            'paths' => [__DIR__],
            '--parallel' => '1',
        ]);
        $display = $this->commandTester->getDisplay();

        $this->assertEquals(0, $exitCode);
        $this->assertEquals(RunCommand::LOGO, substr($display, 0, strlen(RunCommand::LOGO)));
        // there is no progress bar by default
        $this->assertFalse(strpos($display, self::BAR), 'The progress bar shouldn\'t be displayed');
    }

    /** @test */
    public function it_can_show_a_progress_bar()
    {
        $exitCode = $this->commandTester->execute([
            'paths' => [__DIR__],
            '--progress' => null,
        ]);
        $display = $this->commandTester->getDisplay();

        $this->assertEquals(0, $exitCode);
        $this->assertEquals(RunCommand::LOGO, substr($display, 0, strlen(RunCommand::LOGO)));
        // the progress bar must be right after the logo
        $display = ltrim(substr($display, strlen(RunCommand::LOGO)));
        $this->assertEquals(self::BAR, substr($display, 0, strlen(self::BAR)));
    }

    /** @test */
    public function it_can_return_a_json_report()
    {
        $exitCode = $this->commandTester->execute(['paths' => [__DIR__], '--format' => 'json']);
        $data = \json_decode($this->commandTester->getDisplay(), true);

        $this->assertEquals(0, $exitCode);
        $this->assertReport($data);
    }

    /** @test */
    public function it_can_write_a_json_report()
    {
        $this->tmpFile = \tempnam(\sys_get_temp_dir(), 'churn-test-');
        $exitCode = $this->commandTester->execute([
            'paths' => [\realpath(__DIR__ . '/../../')],
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
        $this->assertTrue(is_array($data), 'Expected array, got ' . gettype($data) . ' (' . var_export($data, true) . ')');
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
            'paths' => [__DIR__],
            '--configuration' => 'not a valid configuration file',
        ]);
    }

    /** @test */
    public function it_throws_when_no_directory(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->commandTester->execute([
            'paths' => [],
            '-c' => __DIR__ . '/config/empty.yml',
        ]);
    }

    /** @test */
    public function it_can_use_cache(): void
    {
        // delete cache if any
        $cachePath = $this->tmpFile = __DIR__ . '/config/.churn.cache';
        if (is_file($cachePath)) {
            unlink($cachePath);
        }
        $this->assertFalse(file_exists($cachePath), "File $cachePath shouldn't exist");

        // generate cache
        $exitCode = $this->commandTester->execute([
            'paths' => [],
            '-c' => __DIR__ . '/config/test-cache.yml',
        ]);
        $displayBeforeCache = $this->commandTester->getDisplay();

        $this->assertEquals(0, $exitCode);
        $this->assertTrue(file_exists($cachePath), "File $cachePath should exist");
        $this->assertGreaterThan(0, filesize($cachePath), 'Cache file is empty');

        // use cache
        $exitCode = $this->commandTester->execute([
            'paths' => [],
            '-c' => __DIR__ . '/config/test-cache.yml',
        ]);
        $displayAfterCache = $this->commandTester->getDisplay();

        $this->assertEquals(0, $exitCode);
        $this->assertEquals($displayBeforeCache, $displayAfterCache);
        $this->assertTrue(file_exists($cachePath), "File $cachePath should exist");
        $this->assertGreaterThan(0, filesize($cachePath), 'Cache file is empty');
    }

    /** @test */
    public function it_can_use_a_hook_by_classname(): void
    {
        TestHook::reset();

        $exitCode = $this->commandTester->execute([
            'paths' => [__FILE__, __DIR__ . '/AssessComplexityCommandTest.php'],
            '-c' => __DIR__ . '/config/hook-by-classname.yml',
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertEquals(1, TestHook::$nbAfterAnalysisEvent);
        $this->assertEquals(2, TestHook::$nbAfterFileAnalysisEvent);
        $this->assertEquals(1, TestHook::$nbBeforeAnalysisEvent);
    }

    /** @test */
    public function it_can_use_a_hook_by_path(): void
    {
        $exitCode = $this->commandTester->execute([
            'paths' => [__FILE__, __DIR__ . '/AssessComplexityCommandTest.php'],
            '-c' => __DIR__ . '/config/hook-by-path.yml',
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertEquals(1, TestAfterAnalysisHook::$nbAfterAnalysisEvent);
        $this->assertEquals(2, TestAfterFileAnalysisHook::$nbAfterFileAnalysisEvent);
        $this->assertEquals(1, TestBeforeAnalysisHook::$nbBeforeAnalysisEvent);
    }

    /** @test */
    public function it_throws_for_invalid_hooks(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid hook: invalid-hook');
        $this->commandTester->execute([
            'paths' => [__FILE__, __DIR__ . '/AssessComplexityCommandTest.php'],
            '-c' => __DIR__ . '/config/hook-invalid.yml',
        ]);
    }

    /** @test */
    public function it_can_suppress_normal_output(): void
    {
        TestHook::reset();

        ob_start();
        $exitCode = $this->commandTester->execute([
            'paths' => [__FILE__, __DIR__ . '/AssessComplexityCommandTest.php'],
            '-c' => __DIR__ . '/config/hook-print.yml',
            '--quiet' => null,
        ]);
        $display = ob_get_contents();
        ob_end_clean();

        $this->assertEquals(0, $exitCode);
        $this->assertEquals('Churn: DONE', $display);
    }

    /** @test */
    public function it_can_return_one_as_exit_code(): void
    {
        $exitCode = $this->commandTester->execute([
            'paths' => [__FILE__],
            '-c' => __DIR__ . '/config/test-threshold.yml',
        ], ['capture_stderr_separately' => true]);
        $display = $this->commandTester->getErrorOutput();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Max score is over the threshold', $display);
    }

    /** @test */
    public function it_can_warn_about_unrecognized_keys(): void
    {
        $exitCode = $this->commandTester->execute([
            'paths' => [__FILE__],
            '-c' => __DIR__ . '/config/unrecognized-keys.yml',
        ], ['capture_stderr_separately' => true]);
        $display = $this->commandTester->getErrorOutput();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Unrecognized configuration keys: foo, bar', $display);
    }

    /** @test */
    public function it_can_return_a_json_report_and_also_warn()
    {
        $exitCode = $this->commandTester->execute([
            'paths' => [__DIR__],
            '--format' => 'json',
            '-c' => __DIR__ . '/config/unrecognized-keys.yml',
        ], ['capture_stderr_separately' => true]);
        $display = $this->commandTester->getErrorOutput();
        $data = \json_decode($this->commandTester->getDisplay(), true);

        $this->assertEquals(0, $exitCode);
        $this->assertReport($data);
        $this->assertStringContainsString('Unrecognized configuration keys: foo, bar', $display);
    }
}
