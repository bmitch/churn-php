<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\File;

use Churn\File\File;
use Churn\Tests\BaseTestCase;

class FileTest extends BaseTestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(File::class, $this->file);
    }

    /** @test */
    public function it_can_return_its_values()
    {
        $this->assertSame('foo/bar/baz.php', $this->file->getFullPath());
        $this->assertSame('bar/baz.php', $this->file->getDisplayPath());
    }

    public function setUp()
    {
        parent::setUp();

        $this->file = new File('foo/bar/baz.php', 'bar/baz.php');
    }
}
