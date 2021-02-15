<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration;

use Churn\Configuration\Config;
use Churn\Tests\BaseTestCase;
use InvalidArgumentException;

class ConfigTest extends BaseTestCase
{
    /**
     * @test
     * @dataProvider provide_invalid_values
     */
    public function it_throws_exception_if_badly_instantiated(array $config, string $expectedMessage)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);
        Config::create($config);
    }

    public function provide_invalid_values(): iterable
    {
        yield [
            ['fileExtensions' => 5],
            'File extensions should be an array of strings'
        ];

        yield [
            ['directoriesToScan' => 'not an array'],
            'Directories to scan should be an array of strings'
        ];

        yield [
            ['directoriesToScan' => ['/tmp', 42]],
            'Directories to scan should be an array of strings'
        ];
    }

    /** @test */
    public function it_can_be_instantiated_without_any_parameters()
    {
        $this->assertInstanceOf(Config::class, Config::createFromDefaultValues());
    }

    /** @test */
    public function it_can_return_its_default_values_when_instantiated_without_any_parameters()
    {
        $config = Config::createFromDefaultValues();
        $this->assertSame([], $config->getDirectoriesToScan());
        $this->assertSame(10, $config->getFilesToShow());
        $this->assertSame(0.1, $config->getMinScoreToShow());
        $this->assertSame(null, $config->getMaxScoreThreshold());
        $this->assertSame(10, $config->getParallelJobs());
        $this->assertSame('10 years ago', $config->getCommitsSince());
        $this->assertSame([], $config->getFilesToIgnore());
        $this->assertSame(['php'], $config->getFileExtensions());
        $this->assertSame('git', $config->getVCS());
        $this->assertSame(null, $config->getCachePath());
        $this->assertSame([], $config->getHooks());
    }

    /** @test */
    public function it_can_return_its_values_when_instantiated_parameters()
    {
        $filesToShow = 13;
        $directoriesToScan = ['src', 'tests'];
        $minScoreToShow = 5;
        $maxScoreThreshold = 9.5;
        $parallelJobs = 7;
        $commitsSince = '4 years ago';
        $filesToIgnore = ['foo.php', 'bar.php', 'baz.php'];
        $fileExtensions = ['php', 'inc'];
        $vcs = 'none';
        $cachePath = '/tmp/.churn.cache';
        $hooks = ['Hook1', 'Hook2'];

        $config = Config::create([
            'directoriesToScan' => $directoriesToScan,
            'filesToShow' => $filesToShow,
            'minScoreToShow' => $minScoreToShow,
            'maxScoreThreshold' => $maxScoreThreshold,
            'parallelJobs' => $parallelJobs,
            'commitsSince' => $commitsSince,
            'filesToIgnore' => $filesToIgnore,
            'fileExtensions' => $fileExtensions,
            'vcs' => $vcs,
            'cachePath' => $cachePath,
            'hooks' => $hooks,
        ]);

        $this->assertSame($directoriesToScan, $config->getDirectoriesToScan());
        $this->assertSame($filesToShow, $config->getFilesToShow());
        $this->assertEquals($minScoreToShow, $config->getMinScoreToShow());
        $this->assertSame($maxScoreThreshold, $config->getMaxScoreThreshold());
        $this->assertSame($parallelJobs, $config->getParallelJobs());
        $this->assertSame($commitsSince, $config->getCommitsSince());
        $this->assertSame($filesToIgnore, $config->getFilesToIgnore());
        $this->assertSame($fileExtensions, $config->getFileExtensions());
        $this->assertSame($vcs, $config->getVCS());
        $this->assertSame($cachePath, $config->getCachePath());
        $this->assertSame($hooks, $config->getHooks());
    }

    /** @test */
    public function it_accepts_null_for_min_score_to_show()
    {
        $config = Config::create([
            'minScoreToShow' => null,
        ]);

        $this->assertNull($config->getMinScoreToShow());
    }
}
