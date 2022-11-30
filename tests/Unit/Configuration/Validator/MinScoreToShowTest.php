<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration\Validator;

use Churn\Configuration\Config;
use Churn\Configuration\EditableConfig;
use Churn\Configuration\Validator;
use Churn\Configuration\Validator\MinScoreToShow;

final class MinScoreToShowTest extends ValidatorBaseTestCase
{
    /** {@inheritDoc} */
    protected function getValidator(): Validator
    {
        return new MinScoreToShow();
    }

    /**
     * @param Config $config The configuration object.
     * @return mixed
     */
    protected function getValue(Config $config)
    {
        return $config->getMinScoreToShow();
    }

    /** {@inheritDoc} */
    protected function getDefaultValue()
    {
        return 0.1;
    }

    /** {@inheritDoc} */
    public static function provideValidValues(): iterable
    {
        yield 'MinScoreToShow' => [5.0];
    }

    /** {@inheritDoc} */
    public static function provideInvalidValues(): iterable
    {
        yield 'MinScoreToShow / string' => ['foo', 'Minimum score to show should be a number'];
    }

    /**
     * @test
     */
    public function it_accepts_null(): void
    {
        $config = new EditableConfig();
        // set non-null value to test it will be changed
        $config->setMinScoreToShow(1);
        $validator = $this->getValidator();

        $validator->validate($config, [$validator->getKey() => null]);

        self::assertNull($this->getValue($config));
    }
}
