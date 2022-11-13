<?php

declare(strict_types=1);

namespace Churn\Tests;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    use MockeryPHPUnitIntegration;
}
