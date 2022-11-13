<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\File;

use InvalidArgumentException;
use Churn\File\FileHelper;
use Churn\Tests\BaseTestCase;
use Symfony\Component\Filesystem\Filesystem;

class FileHelperTest extends BaseTestCase
{
    /** @var string[] */
    private $filesToDelete = [];

    protected function tearDown()
    {
        parent::tearDown();

        (new Filesystem())->remove($this->filesToDelete);
    }

    /**
     * @test
     * @dataProvider provide_absolute_paths
     */
    public function it_can_return_absolute_path(string $path, string $confPath, string $expectedPath): void
    {
        self::assertSame($expectedPath, FileHelper::toAbsolutePath($path, $confPath));
    }

    /**
     * @return iterable<array{string, string, string}>
     */
    public function provide_absolute_paths(): iterable
    {
        yield ['/tmp', '/path', '/tmp'];
        yield ['foo', '/path', '/path/foo'];
        yield ['foo', 'C:\\path', 'C:\\path/foo'];
        yield ['C:\\foo', '/path', 'C:\\foo'];
        yield ['d:\\foo', '/path', 'd:\\foo'];
        yield ['E:/foo', '/path', 'E:/foo'];
        yield ['f:/foo', '/path', 'f:/foo'];
    }

    /**
     * @test
     * @dataProvider provide_relative_paths
     */
    public function it_can_return_relative_path(string $path, string $confPath, string $expectedPath): void
    {
        self::assertSame($expectedPath, FileHelper::toRelativePath($path, $confPath));
    }

    /**
     * @return iterable<array{string, string, string}>
     */
    public function provide_relative_paths(): iterable
    {
        yield ['/tmp/file.php', '/tmp', 'file.php'];
        yield ['/tmp/file.php', '/tmp/', 'file.php'];
        yield ['file.php', '/tmp', 'file.php'];
        yield ['C:/foo/file.php', 'C:/foo', 'file.php'];

        if ('\\' === \DIRECTORY_SEPARATOR) {
            yield ['C:\\foo\\file.php', 'C:\\foo\\', 'file.php'];
        }
    }

    /**
     * @test
     * @dataProvider provide_writable_paths
     */
    public function it_can_ensure_a_file_is_writable(string $filePath): void
    {
        $dirPath = \dirname($filePath);

        FileHelper::ensureFileIsWritable($filePath);

        self::assertTrue(\is_dir($dirPath), "Directory should exist: " . $dirPath);
    }

    /**
     * @return iterable<array{string}>
     */
    public function provide_writable_paths(): iterable
    {
        yield [__FILE__];
        yield [__DIR__ . '/non-existing-file'];
        $this->filesToDelete[] = __DIR__ . '/path';
        yield [__DIR__ . '/path/to/non-existing-file'];
    }

    /**
     * @test
     * @dataProvider provide_invalid_writable_paths
     */
    public function it_throws_with_invalid_writable_files(string $filePath): void
    {
        $this->expectException(InvalidArgumentException::class);
        FileHelper::ensureFileIsWritable($filePath);
    }

    /**
     * @return iterable<array{string}>
     */
    public function provide_invalid_writable_paths(): iterable
    {
        yield [''];
        yield [__DIR__];
    }
}
