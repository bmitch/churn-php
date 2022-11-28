<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration\Validator;

use Churn\Configuration\Config;
use Churn\Configuration\EditableConfig;
use Churn\Configuration\Validator;
use Churn\Configuration\Validator\MaxScoreThreshold;
use Churn\Tests\Unit\Configuration\ValidatorBaseTestCase;

final class MaxScoreThresholdTest extends ValidatorBaseTestCase
{
    /** {@inheritDoc} */
    protected function getValidator(): Validator
    {
        return new MaxScoreThreshold();
    }

    /**
     * @param Config $config The configuration object.
     * @return mixed
     */
    protected function getValue(Config $config)
    {
        return $config->getMaxScoreThreshold();
    }

    /** {@inheritDoc} */
    protected function getDefaultValue()
    {
        return null;
    }

    /** {@inheritDoc} */
    public function provideValidValues(): iterable
    {
        yield 'MaxScoreThreshold' => [9.5];
    }

    /** {@inheritDoc} */
    public function provideInvalidValues(): iterable
    {
        yield 'MaxScoreThreshold / string' => ['foo', 'Maximum score threshold should be a number'];
    }

    /**
     * @test
     */
    public function it_accepts_null(): void
    {
        $config = new EditableConfig();
        // set non-null value to test it will be changed
        $config->setMaxScoreThreshold(1.0);
        $validator = $this->getValidator();

        $validator->validate($config, [$validator->getKey() => null]);

        self::assertNull($this->getValue($config));
    }
}
