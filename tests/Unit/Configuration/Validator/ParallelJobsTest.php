<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration\Validator;

use Churn\Configuration\Config;
use Churn\Configuration\Validator;
use Churn\Configuration\Validator\ParallelJobs;
use Churn\Tests\Unit\Configuration\ValidatorBaseTestCase;

final class ParallelJobsTest extends ValidatorBaseTestCase
{
    /** {@inheritDoc} */
    protected function getValidator(): Validator
    {
        return new ParallelJobs();
    }

    /**
     * @param Config $config The configuration object.
     * @return mixed
     */
    protected function getValue(Config $config)
    {
        return $config->getParallelJobs();
    }

    /** {@inheritDoc} */
    protected function getDefaultValue()
    {
        return 10;
    }

    /** {@inheritDoc} */
    public function provideValidValues(): iterable
    {
        yield 'ParallelJobs' => [7];
    }

    /** {@inheritDoc} */
    public function provideInvalidValues(): iterable
    {
        yield 'ParallelJobs / string' => ['foo', 'Amount of parallel jobs should be an integer'];
        yield 'ParallelJobs / null' => [null, 'Amount of parallel jobs should be an integer'];
    }
}
