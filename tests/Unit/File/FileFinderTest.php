<?php declare(strict_types = 1);

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

    /** @test */
    public function it_can_be_created()
    {
        $this->assertInstanceOf(FileFinder::class, $this->fileFinder);
    }

    /** @test */
    public function it_can_get_the_php_files_in_a_filter()
    {
        $this->assertCount(3, iterator_to_array($this->fileFinder->getPhpFiles([__DIR__ . '/../Assets']), false));
    }

    /** @test */
    public function it_can_get_the_php_files_in_multiple_directories()
    {
        $this->assertCount(7, iterator_to_array($this->fileFinder->getPhpFiles([__DIR__ . '/../Assets', __DIR__ . '/../Assets2']), false));
    }

    /** @test */
    public function it_can_get_the_php_files_by_name()
    {
        $this->assertCount(2, iterator_to_array($this->fileFinder->getPhpFiles([__DIR__ . '/../Assets/Bar.php', __DIR__ . '/../Assets/Foo.php']), false));
    }

    /** @test */
    public function it_ignores_files_specified_to_ignore_in_the_config()
    {
        $fileFinder = new FileFinder(['php'], ['Assets/Baz.php']);
        $this->assertCount(2, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets']), false));
    }

    /** @test */
    public function it_ignores_everything_within_a_folder()
    {
        $fileFinder = new FileFinder(['php'], ['Assets2/DeepAssets/*']);
        $this->assertCount(1, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets2']), false));

        $fileFinder = new FileFinder(['php', 'inc'], ['Assets2/DeepAssets/*']);
        $this->assertCount(2, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets2']), false));
    }

    /** @test */
    public function it_ignores_everything_starts_with_a_string()
    {
        $fileFinder = new FileFinder(['php'], ['Assets2/F*']);
        $this->assertCount(3, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets2']), false));

        $fileFinder = new FileFinder(['php'], ['Assets2/DeepAssets/Deep*']);
        $this->assertCount(2, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets2']), false));

        $fileFinder = new FileFinder(['php'], ['Assets2/DeepAssets/Dif*']);
        $this->assertCount(3, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets2']), false));
    }

    /** @test */
    public function it_ignores_multiple_matching_patterns_in_multiple_folders()
    {
        $fileFinder = new FileFinder(['php'], ['Assets2/F*', 'Assets/B*']);
        $this->assertCount(4, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets', __DIR__ . '/../Assets2']), false));

        $fileFinder = new FileFinder(['php', 'inc'], ['Assets2/DeepAssets/De*', 'Assets/B*']);
        $this->assertCount(5, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets', __DIR__ . '/../Assets2']), false));

        $fileFinder = new FileFinder(['php', 'inc'], ['Assets2/DeepAssets/Di*', 'Assets2/DeepAssets/De*', 'Assets2/F*']);
        $this->assertCount(1, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets2']), false));

        $fileFinder = new FileFinder(['php', 'inc'], ['Assets2/*.php']);
        $this->assertCount(1, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets2']), false));
    }

    /** @test */
    public function it_uses_extensions_specified_in_the_config()
    {
        $fileFinder = new FileFinder(['php', 'inc'], []);
        $this->assertCount(4, iterator_to_array($fileFinder->getPhpFiles([__DIR__ . '/../Assets']), false));
    }

    public function setup()
    {
        $this->fileFinder = new FileFinder(['php'], []);
    }
}
