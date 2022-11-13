<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\File;

use Churn\File\File;
use Churn\Tests\BaseTestCase;

class FileTest extends BaseTestCase
{
    /**
     * @var File
     **/
    private $file;

    /** @return void */
    public function setUp()
    {
        parent::setUp();

        $this->file = new File('foo/bar/baz.php', 'bar/baz.php');
    }

    /** @test */
    public function it_can_return_its_values(): void
    {
        self::assertSame('foo/bar/baz.php', $this->file->getFullPath());
        self::assertSame('bar/baz.php', $this->file->getDisplayPath());
    }
}
