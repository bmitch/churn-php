<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration;

use Churn\Configuration\ReadOnlyConfig;
use Churn\Configuration\EditableConfig;
use Churn\Configuration\Loader;
use Churn\Tests\BaseTestCase;
use InvalidArgumentException;

class LoaderTest extends BaseTestCase
{
    /** @test */
    public function it_returns_the_default_values_if_there_is_no_default_file()
    {
        $cwd = \getcwd();
        try {
            chdir(__DIR__);
            $config = Loader::fromPath('churn.yml', true);

            $this->assertEqualsCanonicalizing(new ReadOnlyConfig(), $config);
            $this->assertSame(\getcwd(), $config->getDirPath());
        } finally {
            // restore cwd
            chdir($cwd);
        }
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
        $config = Loader::fromPath($dirPath, false);

        $this->assertEqualsCanonicalizing(new EditableConfig($dirPath . DIRECTORY_SEPARATOR . 'churn.yml.dist'), $config);
        $this->assertSame($dirPath, $config->getDirPath());
    }

    /** @test */
    public function it_fallbacks_on_the_default_distributed_file()
    {
        $cwd = \getcwd();
        $dirPath = \realpath(__DIR__ . '/config/dist');
        try {
            chdir($dirPath);
            $config = Loader::fromPath('churn.yml', true);

            $this->assertEqualsCanonicalizing(new EditableConfig($dirPath . DIRECTORY_SEPARATOR . 'churn.yml.dist'), $config);
            $this->assertSame($dirPath, $config->getDirPath());
        } finally {
            // restore cwd
            chdir($cwd);
        }
    }
}
