<?php

namespace Churn\Tests\Unit\Collections;

use Churn\Collections\FileCollection;
use Churn\Values\File;

class FileCollectionTest extends \Churn\Tests\BaseTestCase
{
    /** @test **/
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(FileCollection::class, new FileCollection());
    }

    /** @test **/
    public function it_can_pop_off_the_next_file()
    {
        $fileCollection = new FileCollection;
        $fileCollection->push(new File(['fullPath' => 'foo.php', 'displayPath' => 'foo.php']));
        $fileCollection->push(new File(['fullPath' => 'bar.php', 'displayPath' => 'bar.php']));
        $fileCollection->push(new File(['fullPath' => 'baz.php', 'displayPath' => 'baz.php']));
        $this->assertCount(3, $fileCollection);
        $poppedFile = $fileCollection->getNextFile();
        $this->assertCount(2, $fileCollection);
    }

    /** @test **/
    public function it_can_determine_if_it_has_files_or_not()
    {
        $fileCollection = new FileCollection;
        $this->assertFalse($fileCollection->hasFiles());
        $fileCollection->push(new File(['fullPath' => 'foo.php', 'displayPath' => 'foo.php']));
        $this->assertTrue($fileCollection->hasFiles());
    }
    
}