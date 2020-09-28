<?php declare(strict_types=1);

namespace Churn\Tests\Unit;

use Churn\ChurnPHPApplication;
use Churn\Tests\BaseTestCase;

class ChurnPHPApplicationTest extends BaseTestCase
{
    const SEMANTIC_VERSIONING_PATTERN = '\d+\.\d+.\d+';

    /** @test */
    public function it_can_provide_version()
    {
        $application = ChurnPHPApplication::create();

        $this->assertRegExp('/^' . self::SEMANTIC_VERSIONING_PATTERN . '$/', $application->getVersion());
    }

    /** @test */
    public function it_can_provide_long_version()
    {
        $application = ChurnPHPApplication::create();

        $this->assertRegExp('/^' . self::SEMANTIC_VERSIONING_PATTERN . '$/', $application->getVersion());
    }
}
