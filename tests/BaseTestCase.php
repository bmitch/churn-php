<?php

declare(strict_types=1);

namespace Churn\Tests;

use Mockery as m;
use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    /**
     * @see https://github.com/phpspec/prophecy/issues/366#issuecomment-359587114
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->addToAssertionCount(m::getContainer()->mockery_getExpectationCount());

        m::close();
    }
}
