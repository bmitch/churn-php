<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\File;

use Churn\File\FileFinder;
use Churn\Tests\BaseTestCase;

class FileFinderTest extends BaseTestCase
{
    /**
     * The object we're testing.
     * @var FileFinder
     */
    protected $fileFinder;

    /** @return void */
    public function setUp()
    {
        parent::setUp();

        $this->fileFinder = new FileFinder(['php'], [], __DIR__);
    }

    /** @test */
    public function it_can_get_the_php_files_in_a_filter(): void
    {
        self::assertCount(3, iterator_to_array($this->fileFinder->getPhpFiles([__DIR__ . '/../Assets']), false));
    }

    /** @test */
    public function it_can_get_the_php_files_in_multiple_directories(): void
    {
        self::assertCount(7, iterator_to_array($this->fileFinder->getPhpFiles([__DIR__ . '/../Assets', __DIR__ . '/../Assets2']), false));
    }

    /** @test */
    public function it_can_get_the_php_files_by_name(): void
    {
        self::assertCount(2, iterator_to_array($this->fileFinder->getPhpFiles([__DIR__ . '/../Assets/Bar.php', __DIR__ . '/../Assets/Foo.php']), false));
    }

    /** @test */
    public function it_does_not_throw_with_non_existing_path(): void
    {
        self::assertCount(0, iterator_to_array($this->fileFinder->getPhpFiles([__DIR__ . '/NotExisting.php']), false));
    }

    /** @test */
    public function it_ignores_files_specified_to_ignore_in_the_config(): void
    {
        $fileFinder = new FileFinder(['php'], ['Assets/Baz.php'], __DIR__);
        self::assertCount(2, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets']), false));
    }

    /** @test */
    public function it_ignores_everything_within_a_folder(): void
    {
        $fileFinder = new FileFinder(['php'], ['Assets2/DeepAssets/*'], __DIR__);
        self::assertCount(1, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets2']), false));

        $fileFinder = new FileFinder(['php', 'inc'], ['Assets2/DeepAssets/*'], __DIR__);
        self::assertCount(2, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets2']), false));
    }

    /** @test */
    public function it_ignores_everything_starts_with_a_string(): void
    {
        $fileFinder = new FileFinder(['php'], ['Assets2/F*'], __DIR__);
        self::assertCount(3, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets2']), false));

        $fileFinder = new FileFinder(['php'], ['Assets2/DeepAssets/Deep*'], __DIR__);
        self::assertCount(2, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets2']), false));

        $fileFinder = new FileFinder(['php'], ['Assets2/DeepAssets/Dif*'], __DIR__);
        self::assertCount(3, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets2']), false));
    }

    /** @test */
    public function it_ignores_multiple_matching_patterns_in_multiple_folders(): void
    {
        $fileFinder = new FileFinder(['php'], ['Assets2/F*', 'Assets/B*'], __DIR__);
        self::assertCount(4, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets', __DIR__ . '/../Assets2']), false));

        $fileFinder = new FileFinder(['php', 'inc'], ['Assets2/DeepAssets/De*', 'Assets/B*'], __DIR__);
        self::assertCount(5, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets', __DIR__ . '/../Assets2']), false));

        $fileFinder = new FileFinder(['php', 'inc'], ['Assets2/DeepAssets/Di*', 'Assets2/DeepAssets/De*', 'Assets2/F*'], __DIR__);
        self::assertCount(1, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets2']), false));

        $fileFinder = new FileFinder(['php', 'inc'], ['Assets2/*.php'], __DIR__);
        self::assertCount(1, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets2']), false));
    }

    /** @test */
    public function it_uses_extensions_specified_in_the_config(): void
    {
        $fileFinder = new FileFinder(['php', 'inc'], [], __DIR__);
        self::assertCount(4, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets']), false));
    }
}
