<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration;

use Churn\Configuration\Config;
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
        $config = new Config();
        $validator->validate($config, []);

        $this->assertSame($defaultValue, $config->$method());
    }

    public function provide_validators_with_default_value(): iterable
    {
        yield [new CachePath(), 'getCachePath', null];
        yield [new CommitsSince(), 'getCommitsSince', '10 years ago'];
        yield [new DirectoriesToScan(), 'getDirectoriesToScan', []];
        yield [new FileExtensions(), 'getFileExtensions', ['php']];
        yield [new FilesToIgnore(), 'getFilesToIgnore', []];
        yield [new FilesToShow(), 'getFilesToShow', 10];
        yield [new Hooks(), 'getHooks', []];
        yield [new MaxScoreThreshold(), 'getMaxScoreThreshold', null];
        yield [new MinScoreToShow(), 'getMinScoreToShow', 0.1];
        yield [new ParallelJobs(), 'getParallelJobs', 10];
        yield [new Vcs(), 'getVCS', 'git'];
    }

    /**
     * @test
     * @dataProvider provide_validators_with_given_value
     */
    public function it_returns_the_given_value(Validator $validator, string $method, $value): void
    {
        $config = new Config();
        $validator->validate($config, [$validator->getKey() => $value]);

        $this->assertSame($value, $config->$method());
    }

    public function provide_validators_with_given_value(): iterable
    {
        yield [new CachePath(), 'getCachePath', '/tmp/.churn.cache'];
        yield [new CommitsSince(), 'getCommitsSince', '4 years ago'];
        yield [new DirectoriesToScan(), 'getDirectoriesToScan', ['src', 'tests']];
        yield [new FileExtensions(), 'getFileExtensions', ['php', 'inc']];
        yield [new FilesToIgnore(), 'getFilesToIgnore', ['foo.php', 'bar.php', 'baz.php']];
        yield [new FilesToShow(), 'getFilesToShow', 13];
        yield [new Hooks(), 'getHooks', ['Hook1', 'Hook2']];
        yield [new MaxScoreThreshold(), 'getMaxScoreThreshold', 9.5];
        yield [new MinScoreToShow(), 'getMinScoreToShow', 5.0];
        yield [new ParallelJobs(), 'getParallelJobs', 7];
        yield [new Vcs(), 'getVCS', 'none'];
    }

    /**
     * @test
     * @dataProvider provide_validators_accepting_null
     */
    public function it_accepts_null(Validator $validator, string $method): void
    {
        $config = new Config();
        $validator->validate($config, [$validator->getKey() => null]);

        $this->assertNull($config->$method());
    }

    public function provide_validators_accepting_null(): iterable
    {
        yield [new CachePath(), 'getCachePath'];
        yield [new MaxScoreThreshold(), 'getMaxScoreThreshold'];
        yield [new MinScoreToShow(), 'getMinScoreToShow'];
    }

    /**
     * @test
     * @dataProvider provide_validators_with_invalid_value
     */
    public function it_throws_with_invalid_value(Validator $validator, $invalidValue): void
    {
        $config = new Config();
        $this->expectException(InvalidArgumentException::class);
        $validator->validate($config, [$validator->getKey() => $invalidValue]);
    }

    public function provide_validators_with_invalid_value(): iterable
    {
        yield [new CachePath(), 123];
        yield [new CommitsSince(), 123];
        yield [new CommitsSince(), null];
        yield [new DirectoriesToScan(), 'foo'];
        yield [new DirectoriesToScan(), null];
        yield [new FileExtensions(), 'foo'];
        yield [new FileExtensions(), null];
        yield [new FilesToIgnore(), 'foo'];
        yield [new FilesToIgnore(), null];
        yield [new FilesToShow(), 'foo'];
        yield [new FilesToShow(), null];
        yield [new Hooks(), 'foo'];
        yield [new Hooks(), null];
        yield [new MaxScoreThreshold(), 'foo'];
        yield [new MinScoreToShow(), 'foo'];
        yield [new ParallelJobs(), 'foo'];
        yield [new ParallelJobs(), null];
        yield [new Vcs(), 123];
        yield [new Vcs(), null];
    }
}
