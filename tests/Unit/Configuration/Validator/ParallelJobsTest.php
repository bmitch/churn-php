<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration\Validator;

use Churn\Configuration\Config;
use Churn\Configuration\Validator;
use Churn\Configuration\Validator\ParallelJobs;

final class ParallelJobsTest extends ValidatorBaseTestCase
{
    /** {@inheritDoc} */
    #[\Override]
    protected function getValidator(): Validator
    {
        return new ParallelJobs();
    }

    /**
     * @param Config $config The configuration object.
     * @return mixed
     */
    #[\Override]
    protected function getValue(Config $config)
    {
        return $config->getParallelJobs();
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function getDefaultValue()
    {
        return 10;
    }

    /** {@inheritDoc} */
    #[\Override]
    public static function provideValidValues(): iterable
    {
        yield 'ParallelJobs' => [7];
    }

    /** {@inheritDoc} */
    #[\Override]
    public static function provideInvalidValues(): iterable
    {
        yield 'ParallelJobs / string' => ['foo', 'Amount of parallel jobs should be an integer'];
        yield 'ParallelJobs / null' => [null, 'Amount of parallel jobs should be an integer'];
    }
}
