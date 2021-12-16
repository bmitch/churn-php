<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration;

use Churn\Configuration\Config;
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
}
