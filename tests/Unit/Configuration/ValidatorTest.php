<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration;

use Churn\Configuration\EditableConfig;
use Churn\Configuration\Validator;
use Churn\Configuration\Validator\CachePath;
use Churn\Configuration\Validator\CommitsSince;
use Churn\Configuration\Validator\DirectoriesToScan;
use Churn\Configuration\Validator\FileExtensions;
use Churn\Configuration\Validator\FilesToIgnore;
use Churn\Configuration\Validator\FilesToShow;
use Churn\Configuration\Validator\Hooks;
use Churn\Configuration\Validator\MaxScoreThreshold;
use Churn\Configuration\Validator\MinScoreToShow;
use Churn\Configuration\Validator\ParallelJobs;
use Churn\Configuration\Validator\Vcs;
use Churn\Tests\BaseTestCase;
use InvalidArgumentException;

class ValidatorTest extends BaseTestCase
{
    /**
     * @test
     * @dataProvider provide_validators_with_default_value
     */
    public function it_returns_the_default_value(Validator $validator, string $method, $defaultValue): void
    {
        $config = new EditableConfig();
        $validator->validate($config, []);

        $this->assertSame($defaultValue, $config->$method());
    }

    public function provide_validators_with_default_value(): iterable
    {
        yield 'CachePath' => [new CachePath(), 'getCachePath', null];
        yield 'CommitsSince' => [new CommitsSince(), 'getCommitsSince', '10 years ago'];
        yield 'DirectoriesToScan' => [new DirectoriesToScan(), 'getDirectoriesToScan', []];
        yield 'FileExtensions' => [new FileExtensions(), 'getFileExtensions', ['php']];
        yield 'FilesToIgnore' => [new FilesToIgnore(), 'getFilesToIgnore', []];
        yield 'FilesToShow' => [new FilesToShow(), 'getFilesToShow', 10];
        yield 'Hooks' => [new Hooks(), 'getHooks', []];
        yield 'MaxScoreThreshold' => [new MaxScoreThreshold(), 'getMaxScoreThreshold', null];
        yield 'MinScoreToShow' => [new MinScoreToShow(), 'getMinScoreToShow', 0.1];
        yield 'ParallelJobs' => [new ParallelJobs(), 'getParallelJobs', 10];
        yield 'Vcs' => [new Vcs(), 'getVCS', 'git'];
    }

    /**
     * @test
     * @dataProvider provide_validators_with_given_value
     */
    public function it_returns_the_given_value(Validator $validator, string $method, $value): void
    {
        $config = new EditableConfig();
        $validator->validate($config, [$validator->getKey() => $value]);

        $this->assertSame($value, $config->$method());
    }

    public function provide_validators_with_given_value(): iterable
    {
        yield 'CachePath' => [new CachePath(), 'getCachePath', '/tmp/.churn.cache'];
        yield 'CommitsSince' => [new CommitsSince(), 'getCommitsSince', '4 years ago'];
        yield 'DirectoriesToScan' => [new DirectoriesToScan(), 'getDirectoriesToScan', ['src', 'tests']];
        yield 'FileExtensions' => [new FileExtensions(), 'getFileExtensions', ['php', 'inc']];
        yield 'FilesToIgnore' => [new FilesToIgnore(), 'getFilesToIgnore', ['foo.php', 'bar.php', 'baz.php']];
        yield 'FilesToShow' => [new FilesToShow(), 'getFilesToShow', 13];
        yield 'Hooks' => [new Hooks(), 'getHooks', ['Hook1', 'Hook2']];
        yield 'MaxScoreThreshold' => [new MaxScoreThreshold(), 'getMaxScoreThreshold', 9.5];
        yield 'MinScoreToShow' => [new MinScoreToShow(), 'getMinScoreToShow', 5.0];
        yield 'ParallelJobs' => [new ParallelJobs(), 'getParallelJobs', 7];
        yield 'Vcs' => [new Vcs(), 'getVCS', 'none'];
    }

    /**
     * @test
     * @dataProvider provide_validators_accepting_null
     */
    public function it_accepts_null(Validator $validator, string $method): void
    {
        $config = new EditableConfig();
        // set non-null values to test they will be changed
        $config->setCachePath('/cache/path');
        $config->setMaxScoreThreshold(1.0);
        $config->setMinScoreToShow(1);

        $validator->validate($config, [$validator->getKey() => null]);

        $this->assertNull($config->$method());
    }

    public function provide_validators_accepting_null(): iterable
    {
        yield 'CachePath' => [new CachePath(), 'getCachePath'];
        yield 'MaxScoreThreshold' => [new MaxScoreThreshold(), 'getMaxScoreThreshold'];
        yield 'MinScoreToShow' => [new MinScoreToShow(), 'getMinScoreToShow'];
    }

    /**
     * @test
     * @dataProvider provide_validators_with_invalid_value
     */
    public function it_throws_with_invalid_value(Validator $validator, $invalidValue, string $errorMessage): void
    {
        $config = new EditableConfig();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($errorMessage);
        $validator->validate($config, [$validator->getKey() => $invalidValue]);
    }

    public function provide_validators_with_invalid_value(): iterable
    {
        yield 'CachePath / int' => [new CachePath(), 123, 'Cache path should be a string'];
        yield 'CommitsSince / int' => [new CommitsSince(), 123, 'Commits since should be a string'];
        yield 'CommitsSince / null' => [new CommitsSince(), null, 'Commits since should be a string'];
        yield 'DirectoriesToScan / string' => [new DirectoriesToScan(), 'foo', 'Directories to scan should be an array of strings'];
        yield 'DirectoriesToScan / null' => [new DirectoriesToScan(), null, 'Directories to scan should be an array of strings'];
        yield 'FileExtensions / string' => [new FileExtensions(), 'foo', 'File extensions should be an array of strings'];
        yield 'FileExtensions / null' => [new FileExtensions(), null, 'File extensions should be an array of strings'];
        yield 'FilesToIgnore / string' => [new FilesToIgnore(), 'foo', 'Files to ignore should be an array of strings'];
        yield 'FilesToIgnore / null' => [new FilesToIgnore(), null, 'Files to ignore should be an array of strings'];
        yield 'FilesToShow / string' => [new FilesToShow(), 'foo', 'Files to show should be an integer'];
        yield 'FilesToShow / null' => [new FilesToShow(), null, 'Files to show should be an integer'];
        yield 'Hooks / string' => [new Hooks(), 'foo', 'Hooks should be an array of strings'];
        yield 'Hooks / null' => [new Hooks(), null, 'Hooks should be an array of strings'];
        yield 'MaxScoreThreshold / string' => [new MaxScoreThreshold(), 'foo', 'Maximum score threshold should be a number'];
        yield 'MinScoreToShow / string' => [new MinScoreToShow(), 'foo', 'Minimum score to show should be a number'];
        yield 'ParallelJobs / string' => [new ParallelJobs(), 'foo', 'Amount of parallel jobs should be an integer'];
        yield 'ParallelJobs / null' => [new ParallelJobs(), null, 'Amount of parallel jobs should be an integer'];
        yield 'Vcs / int' => [new Vcs(), 123, 'VCS should be a string'];
        yield 'Vcs / null' => [new Vcs(), null, 'VCS should be a string'];
    }

    /**
     * @test
     */
    public function it_emits_a_deprecation_warning_for_commit_since(): void
    {
        $deprecationMessage = null;
        set_error_handler(function ($_, $errstr) use (&$deprecationMessage) {
            $deprecationMessage = $errstr;

            return true;
        }, \E_USER_DEPRECATED);

        try {
            $config = new EditableConfig();
            $validator = new CommitsSince();
            $validator->validate($config, ['commitSince' => 'one day ago']);

            $this->assertEquals('one day ago', $config->getCommitsSince());
            $this->assertEquals('commitSince', $validator->getKey());
	} finally {
            restore_error_handler();
        }

        $this->assertEquals(
            'The "commitSince" configuration key is deprecated and won\'t be supported'
            . ' in the next major version anymore. Use "commitsSince" instead.',
            $deprecationMessage
        );
    }
}
