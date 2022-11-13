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

    /** @return void */
    protected function setUp()
    {
        parent::setUp();

        $application = new Application('churn-php', 'test');
        $application->add(RunCommand::newInstance());
        $command = $application->find('run');
        $this->commandTester = new CommandTester($command);
    }

    /** @return void */
    protected function tearDown()
    {
        parent::tearDown();

        unset($this->commandTester);

        if ($this->tmpFile !== null && \is_file($this->tmpFile)) {
            \unlink($this->tmpFile);
            $this->tmpFile = null;
        }
    }

    /** @test */
    public function it_displays_the_logo_at_the_beginning_by_default(): void
    {
        $exitCode = $this->commandTester->execute([
            'paths' => [__DIR__],
            '--parallel' => '1',
        ]);
        $display = $this->commandTester->getDisplay();

        self::assertSame(0, $exitCode);
        self::assertSame(RunCommand::LOGO, substr($display, 0, strlen(RunCommand::LOGO)));
        // there is no progress bar by default
        self::assertFalse(strpos($display, self::BAR), 'The progress bar shouldn\'t be displayed');
    }

    /** @test */
    public function it_can_show_a_progress_bar(): void
    {
        $exitCode = $this->commandTester->execute([
            'paths' => [__DIR__],
            '--progress' => null,
        ]);
        $display = $this->commandTester->getDisplay();

        self::assertSame(0, $exitCode);
        self::assertSame(RunCommand::LOGO, substr($display, 0, strlen(RunCommand::LOGO)));
        // the progress bar must be right after the logo
        $display = ltrim(substr($display, strlen(RunCommand::LOGO)));
        self::assertSame(self::BAR, substr($display, 0, strlen(self::BAR)));
    }

    /** @test */
    public function it_can_return_a_json_report(): void
    {
        $exitCode = $this->commandTester->execute(['paths' => [__DIR__], '--format' => 'json']);
        $data = \json_decode($this->commandTester->getDisplay(), true);

        self::assertSame(0, $exitCode);
        self::assertReport($data);
    }

    /** @test */
    public function it_can_write_a_json_report(): void
    {
        self::assertNotFalse($tmpFile = \tempnam(\sys_get_temp_dir(), 'churn-test-'));
        $this->tmpFile = $tmpFile;
        $exitCode = $this->commandTester->execute([
            'paths' => [\realpath(__DIR__ . '/../../')],
            '--format' => 'json',
            '--output' => $this->tmpFile,
        ]);
        $display = $this->commandTester->getDisplay();

        self::assertSame(0, $exitCode);
        self::assertSame(RunCommand::LOGO, substr($display, 0, strlen(RunCommand::LOGO)));

        self::assertFileExists($tmpFile);
        self::assertNotFalse($contents = \file_get_contents($tmpFile));
        $data = \json_decode($contents, true);
        self::assertReport($data);
    }

    /**
     * @param mixed $data
     */
    private static function assertReport($data): void
    {
        self::assertTrue(is_array($data), 'Expected array, got ' . gettype($data) . ' (' . var_export($data, true) . ')');
        $i = 0;
        foreach ($data as $key => $value) {
            self::assertSame($i++, $key);
            self::assertArrayHasKey('file', $value);
            self::assertArrayHasKey('commits', $value);
            self::assertArrayHasKey('complexity', $value);
            self::assertArrayHasKey('score', $value);
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
    public function it_throws_for_invalid_parallel(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->commandTester->execute([
            'paths' => [__DIR__],
            '--parallel' => 'foo',
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
        self::assertFalse(file_exists($cachePath), "File $cachePath shouldn't exist");

        // generate cache
        $exitCode = $this->commandTester->execute([
            'paths' => [],
            '-c' => __DIR__ . '/config/test-cache.yml',
        ]);
        $displayBeforeCache = $this->commandTester->getDisplay();

        self::assertSame(0, $exitCode);
        self::assertTrue(file_exists($cachePath), "File $cachePath should exist");
        self::assertGreaterThan(0, filesize($cachePath), 'Cache file is empty');

        // use cache
        $exitCode = $this->commandTester->execute([
            'paths' => [],
            '-c' => __DIR__ . '/config/test-cache.yml',
        ]);
        $displayAfterCache = $this->commandTester->getDisplay();

        self::assertSame(0, $exitCode);
        self::assertSame($displayBeforeCache, $displayAfterCache);
        self::assertTrue(file_exists($cachePath), "File $cachePath should exist");
        self::assertGreaterThan(0, filesize($cachePath), 'Cache file is empty');
    }

    /** @test */
    public function it_can_use_a_hook_by_classname(): void
    {
        TestHook::reset();

        $exitCode = $this->commandTester->execute([
            'paths' => [__FILE__, __DIR__ . '/AssessComplexityCommandTest.php'],
            '-c' => __DIR__ . '/config/hook-by-classname.yml',
        ]);

        self::assertSame(0, $exitCode);
        self::assertSame(1, TestHook::$nbAfterAnalysisEvent);
        self::assertSame(2, TestHook::$nbAfterFileAnalysisEvent);
        self::assertSame(1, TestHook::$nbBeforeAnalysisEvent);
    }

    /** @test */
    public function it_can_use_a_hook_by_path(): void
    {
        $exitCode = $this->commandTester->execute([
            'paths' => [__FILE__, __DIR__ . '/AssessComplexityCommandTest.php'],
            '-c' => __DIR__ . '/config/hook-by-path.yml',
        ]);

        self::assertSame(0, $exitCode);
        self::assertSame(1, TestAfterAnalysisHook::$nbAfterAnalysisEvent);
        self::assertSame(2, TestAfterFileAnalysisHook::$nbAfterFileAnalysisEvent);
        self::assertSame(1, TestBeforeAnalysisHook::$nbBeforeAnalysisEvent);
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

        self::assertSame(0, $exitCode);
        self::assertSame('Churn: DONE', $display);
    }

    /** @test */
    public function it_can_return_one_as_exit_code(): void
    {
        $exitCode = $this->commandTester->execute([
            'paths' => [__FILE__],
            '-c' => __DIR__ . '/config/test-threshold.yml',
        ], ['capture_stderr_separately' => true]);
        $display = $this->commandTester->getErrorOutput();

        self::assertSame(1, $exitCode);
        self::assertStringContainsString('Max score is over the threshold', $display);
    }

    /** @test */
    public function it_can_warn_about_unrecognized_keys(): void
    {
        $exitCode = $this->commandTester->execute([
            'paths' => [__FILE__],
            '-c' => __DIR__ . '/config/unrecognized-keys.yml',
        ], ['capture_stderr_separately' => true]);
        $display = $this->commandTester->getErrorOutput();

        self::assertSame(0, $exitCode);
        self::assertStringContainsString('Unrecognized configuration keys: foo, bar', $display);
    }

    /** @test */
    public function it_can_return_a_json_report_and_also_warn(): void
    {
        $exitCode = $this->commandTester->execute([
            'paths' => [__DIR__],
            '--format' => 'json',
            '-c' => __DIR__ . '/config/unrecognized-keys.yml',
        ], ['capture_stderr_separately' => true]);
        $display = $this->commandTester->getErrorOutput();
        $data = \json_decode($this->commandTester->getDisplay(), true);

        self::assertSame(0, $exitCode);
        self::assertReport($data);
        self::assertStringContainsString('Unrecognized configuration keys: foo, bar', $display);
    }
}
