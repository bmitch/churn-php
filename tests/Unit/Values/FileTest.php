<?php


namespace Churn\Tests\Unit\Values;


use Churn\Tests\BaseTestCase;
use Churn\Values\File;

class FileTest extends BaseTestCase
{
    /** @test **/
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(File::class, $this->file);
    }

    /** @test **/
    public function it_can_return_its_values()
    {
        $this->assertSame('foo/bar/baz.php', $this->file->getFullPath());
        $this->assertSame('bar/baz.php', $this->file->getDisplayPath());
    }

    public function setUp()
    {
        parent::setUp();

        $this->file = new File(['fullPath' => 'foo/bar/baz.php', 'displayPath' => 'bar/baz.php']);
    }
}