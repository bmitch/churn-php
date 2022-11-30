<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration\Validator;

use Churn\Configuration\Config;
use Churn\Configuration\Validator;
use Churn\Configuration\Validator\CommitsSince;

final class CommitsSinceTest extends ValidatorBaseTestCase
{
    /**
     * @var CommitsSince
     */
    private $validator;

    /** @return void */
    public function setUp()
    {
        parent::setUp();

        $this->validator = new CommitsSince();
    }

    /** {@inheritDoc} */
    protected function getValidator(): Validator
    {
        return $this->validator;
    }

    /**
     * @param Config $config The configuration object.
     * @return mixed
     */
    protected function getValue(Config $config)
    {
        return $config->getCommitsSince();
    }

    /** {@inheritDoc} */
    protected function getDefaultValue()
    {
        return '10 years ago';
    }

    /** {@inheritDoc} */
    public static function provideValidValues(): iterable
    {
        yield 'CommitsSince' => ['4 years ago'];
    }

    /** {@inheritDoc} */
    public static function provideInvalidValues(): iterable
    {
        yield 'CommitsSince / int' => [123, 'Commits since should be a string'];
        yield 'CommitsSince / null' => [null, 'Commits since should be a string'];
    }

    /**
     * @test
     */
    public function it_emits_a_deprecation_warning_for_commit_since(): void
    {
        $deprecationHandler = new DeprecationHandler();

        \set_error_handler($deprecationHandler, \E_USER_DEPRECATED);

        try {
            $this->validator->validate($this->config, ['commitSince' => 'one day ago']);

            self::assertSame('one day ago', $this->config->getCommitsSince());
            self::assertSame('commitSince', $this->validator->getKey());
        } finally {
            \restore_error_handler();
        }

        self::assertSame(
            'The "commitSince" configuration key is deprecated and won\'t be supported'
            . ' in the next major version anymore. Use "commitsSince" instead.',
            $deprecationHandler->getMessage()
        );
    }
}
