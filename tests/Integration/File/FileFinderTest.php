<?php

declare(strict_types=1);

namespace Churn\Tests\Integration\File;

use Churn\File\File;
use Churn\File\FileFinder;
use Churn\Tests\BaseTestCase;

class FileFinderTest extends BaseTestCase
{
    /**
     * The class being tested.
     * @var FileFinder
     */
    private $fileFinder;

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(FileFinder::class, $this->fileFinder);
    }

    /** @test */
    public function it_can_recursively_get_the_php_files_in_a_path()
    {
        $paths = [__DIR__];
        $results = iterator_to_array($this->fileFinder->getPhpFiles($paths), false);
        $this->assertCount(1, $results);
        $this->assertInstanceOf(File::class, $results[0]);
    }

    public function setUp()
    {
        parent::setup();

        $this->fileFinder = new FileFinder(['php'], [], __DIR__);
    }
}
