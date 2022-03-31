<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration;

use Churn\Configuration\Config;
use Churn\Configuration\ReadOnlyConfig;
use Churn\Tests\BaseTestCase;
use InvalidArgumentException;

class ReadOnlyConfigTest extends BaseTestCase
{
    /** @test */
    public function it_can_be_instantiated_without_any_parameters()
    {
        $this->assertInstanceOf(Config::class, new ReadOnlyConfig());
    }

    /** @test */
    public function it_returns_the_current_working_directory_by_default()
    {
        $config = new ReadOnlyConfig();

        $this->assertSame(\getcwd(), $config->getDirPath());
    }

    /** @test */
    public function it_returns_the_right_dir_path()
    {
        $config = new ReadOnlyConfig('/path/to/config/file.yml');

        $this->assertSame('/path/to/config', $config->getDirPath());
    }
}
