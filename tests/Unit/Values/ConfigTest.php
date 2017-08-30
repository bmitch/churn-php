<?php


namespace Churn\Tests\Unit\Values;


use Churn\Tests\BaseTestCase;
use Churn\Values\Config;

class ConfigTest extends BaseTestCase
{
    /** @test **/
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Config::class, new Config([]));
    }

    /** @test **/
    public function it_can_be_instantiated_without_any_parameters()
    {
        $this->assertInstanceOf(Config::class, new Config);
    }

    /** @test **/
    public function it_can_return_its_default_values_when_instantiated_without_any_parameters()
    {
        $config = new Config;
        $this->assertSame(10, $config->getFilesToShow());
        $this->assertSame(0, $config->getMinScoreToShow());
        $this->assertSame(10, $config->getParallelJobs());
        $this->assertSame('10 years ago', $config->getCommitsSince());
        $this->assertSame([], $config->getFilesToIgnore());
    }

    /** @test **/
    public function it_can_return_its_values_when_instantiated_parameters()
    {
        $config = new Config([
            'filesToShow' => 13,
            'minScoreToShow' => 5,
            'parallelJobs' => 7,
            'commitsSince' => '4 years ago',
            'filesToIgnore' => ['foo.php', 'bar.php', 'baz.php']
        ]);
        $this->assertSame(13, $config->getFilesToShow());
        $this->assertSame(5, $config->getMinScoreToShow());
        $this->assertSame(7, $config->getParallelJobs());
        $this->assertSame('4 years ago', $config->getCommitsSince());
        $this->assertSame(['foo.php', 'bar.php', 'baz.php'], $config->getFilesToIgnore());
    }

}