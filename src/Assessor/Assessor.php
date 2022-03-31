<?php

declare(strict_types=1);

namespace Churn\Assessor;

/**
 * @internal
 */
interface Assessor
{
    /**
     * Assess a PHP file.
     *
     * @param string $contents The contents of a PHP file.
     */
    public function assess(string $contents): int;
}
