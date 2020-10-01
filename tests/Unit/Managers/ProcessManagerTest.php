<?php declare(strict_types = 1);

namespace Churn\Tests\Unit\Managers;

use Churn\Configuration\Config;
use Churn\Managers\ProcessManager;
use Churn\Tests\BaseTestCase;
use Churn\Values\File;

class ProcessManagerTest extends BaseTestCase
{
    /** @test */
    public function it_can_be_instantiated() 
    {
        $this->assertInstanceOf(ProcessManager::class, new ProcessManager);
    }
}

?>

