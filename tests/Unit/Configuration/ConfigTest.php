<?php


namespace Churn\Tests\Unit\Configuration;


use Churn\Configuration\Config;
use Churn\Tests\BaseTestCase;

class ConfigTest extends BaseTestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     **/
    public function it_throws_exception_if_badly_instantiated()
    {
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
        $this->assertSame(Config::DIRECTORIES_TO_SCAN, $config->getDirectoriesToScan());
        $this->assertSame(Config::FILES_TO_SHOW, $config->getFilesToShow());
        $this->assertSame(Config::MINIMUM_SCORE_TO_SHOW, $config->getMinScoreToShow());
        $this->assertSame(Config::AMOUNT_OF_PARALLEL_JOBS, $config->getParallelJobs());
        $this->assertSame(Config::SHOW_COMMITS_SINCE, $config->getCommitsSince());
        $this->assertSame(Config::FILES_TO_IGNORE, $config->getFilesToIgnore());
        $this->assertSame(Config::FILE_EXTENSIONS_TO_PARSE, $config->getFileExtensions());
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

        $config = Config::create([
            'directoriesToScan' => $directoriesToScan,
            'filesToShow' => $filesToShow,
            'minScoreToShow' => $minScoreToShow,
            'parallelJobs' => $parallelJobs,
            'commitsSince' => $commitsSince,
            'filesToIgnore' => $filesToIgnore,
            'fileExtensions' => $fileExtensions,
        ]);

        $this->assertSame($directoriesToScan, $config->getDirectoriesToScan());
        $this->assertSame($filesToShow, $config->getFilesToShow());
        $this->assertEquals($minScoreToShow, $config->getMinScoreToShow());
        $this->assertSame($parallelJobs, $config->getParallelJobs());
        $this->assertSame($commitsSince, $config->getCommitsSince());
        $this->assertSame($filesToIgnore, $config->getFilesToIgnore());
        $this->assertSame($fileExtensions, $config->getFileExtensions());
    }

}
