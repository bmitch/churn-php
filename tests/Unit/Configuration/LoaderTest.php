<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration;

use Churn\Configuration\Config;
use Churn\Configuration\EditableConfig;
use Churn\Configuration\Loader;
use Churn\Tests\BaseTestCase;
use InvalidArgumentException;

class LoaderTest extends BaseTestCase
{
    /** @test */
    public function it_returns_the_default_values_if_there_is_no_default_file()
    {
        $config = Loader::fromPath('non-existing-config-file.yml', true);

        $this->assertEquals(new Config(), $config);
        $this->assertEquals(\getcwd(), $config->getDirPath());
    }

    /** @test */
    public function it_throws_if_the_chosen_file_is_missing()
    {
        $this->expectException(InvalidArgumentException::class);
        Loader::fromPath('non-existing-config-file.yml', false);
    }

    /** @test */
    public function it_throws_if_the_content_is_invalid()
    {
        $this->expectException(InvalidArgumentException::class);
        Loader::fromPath(__FILE__, false);
    }

    /** @test */
    public function it_fallbacks_on_the_distributed_file()
    {
        $dirPath = \realpath(__DIR__ . '/config/dist');
        $config = Loader::fromPath($dirPath, true);

        $this->assertEquals(new EditableConfig($dirPath . DIRECTORY_SEPARATOR . 'churn.yml.dist'), $config);
        $this->assertEquals($dirPath, $config->getDirPath());
    }

    /** @test */
    public function it_fallbacks_on_the_default_distributed_file()
    {
        $cwd = \getcwd();
        $dirPath = \realpath(__DIR__ . '/config/dist');
        try {
            chdir($dirPath);
            $config = Loader::fromPath('churn.yml', true);

            $this->assertEquals(new EditableConfig($dirPath . DIRECTORY_SEPARATOR . 'churn.yml.dist'), $config);
            $this->assertEquals($dirPath, $config->getDirPath());
        } finally {
            // restore cwd
            chdir($cwd);
        }
    }
}
