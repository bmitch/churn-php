<?php

declare(strict_types=1);

namespace Churn\Tests\Unit\Configuration\Validator;

final class DeprecationHandler
{
    /**
     * @var string The deprecation error message.
     */
    private $message = '';

    /**
     * @param integer $_ Error code, to ignore.
     * @param string $errstr The error message.
     */
    public function __invoke(int $_, string $errstr): bool
    {
        $this->message = $errstr;

        return true;
    }

    /**
     * @return string The deprecation error message.
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
