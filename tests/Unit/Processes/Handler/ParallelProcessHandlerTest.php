<?php declare(strict_types = 1);

namespace Churn\Tests\Unit\Processes\Handler;

use Churn\Collections\FileCollection;
use Churn\Configuration\Config;
use Churn\Processes\Handler\ParallelProcessHandler;
use Churn\Processes\ProcessFactory;
use Churn\Tests\BaseTestCase;
use Churn\Values\File;

class ParallelProcessHandlerTest extends BaseTestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(ParallelProcessHandler::class, new ParallelProcessHandler(2));
    }

    /** @test */
    public function it_returns_empty_collection_when_no_files()
    {
        $processHandler = new ParallelProcessHandler(3);
        $config = Config::createFromDefaultValues();
        $processFactory = new ProcessFactory($config->getCommitsSince());
        $fileCollection = new FileCollection();
        
        $collection = $processHandler->process($fileCollection, $processFactory);
        $this->assertEquals($collection->count(), 0);
    }
}
