<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\File;

use Churn\File\FileHelper;
use Churn\Tests\BaseTestCase;

class FileHelperTest extends BaseTestCase
{

    /**
     * @test
     * @dataProvider provide_paths
     */
    public function it_can_return_absolute_path(string $path, string $confPath, string $expectedPath): void
    {
        $this->assertEquals($expectedPath, FileHelper::toAbsolutePath($path, $confPath));
    }

    public function provide_paths(): iterable
    {
        yield ['/tmp', '/path', '/tmp'];
        yield ['foo', '/path', '/path/foo'];
        yield ['C:\\foo', '/path', 'C:\\foo'];
        yield ['d:\\foo', '/path', 'd:\\foo'];
        yield ['E:/foo', '/path', 'E:/foo'];
        yield ['f:/foo', '/path', 'f:/foo'];
        yield ['://foo', '/path', '://foo'];
    }
}
