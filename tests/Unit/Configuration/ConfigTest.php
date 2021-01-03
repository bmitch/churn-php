<?php declare(strict_types = 1);

namespace Churn\Tests\Unit\Configuration;

use Churn\Configuration\Config;
use Churn\Tests\BaseTestCase;
use InvalidArgumentException;

class ConfigTest extends BaseTestCase
{
    /** @test */
    public function it_throws_exception_if_badly_instantiated()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->assertInstanceOf(
            Config::class,
            Config::create(['fileExtensions' => 5])
        );
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
        $this->assertSame(10, $config->getParallelJobs());
        $this->assertSame('10 years ago', $config->getCommitsSince());
        $this->assertSame([], $config->getFilesToIgnore());
        $this->assertSame(['php'], $config->getFileExtensions());
        $this->assertSame('git', $config->getVCS());
    }

    /** @test */
    public function it_can_return_its_values_when_instantiated_parameters()
    {
        $filesToShow = 13;

        $directoriesToScan = ['src', 'tests'];
        $minScoreToShow = 5;
        $parallelJobs = 7;
        $commitsSince = '4 years ago';
        $filesToIgnore = ['foo.php', 'bar.php', 'baz.php'];
        $fileExtensions = ['php', 'inc'];
        $vcs = 'none';

        $config = Config::create([
            'directoriesToScan' => $directoriesToScan,
            'filesToShow' => $filesToShow,
            'minScoreToShow' => $minScoreToShow,
            'parallelJobs' => $parallelJobs,
            'commitsSince' => $commitsSince,
            'filesToIgnore' => $filesToIgnore,
            'fileExtensions' => $fileExtensions,
            'vcs' => $vcs,
        ]);

        $this->assertSame($directoriesToScan, $config->getDirectoriesToScan());
        $this->assertSame($filesToShow, $config->getFilesToShow());
        $this->assertEquals($minScoreToShow, $config->getMinScoreToShow());
        $this->assertSame($parallelJobs, $config->getParallelJobs());
        $this->assertSame($commitsSince, $config->getCommitsSince());
        $this->assertSame($filesToIgnore, $config->getFilesToIgnore());
        $this->assertSame($fileExtensions, $config->getFileExtensions());
        $this->assertSame($vcs, $config->getVCS());
    }
}
