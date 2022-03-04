<?php

declare(strict_types=1);

namespace Churn\Assessor;

/**
 * @internal
 */
class CyclomaticComplexityAssessor
{
    /**
     * @var array<int, int>
     */
    private $tokens = [
        \T_CLASS => 1,
        \T_INTERFACE => 1,
        \T_TRAIT => 1,
        \T_IF => 1,
        \T_ELSEIF => 1,
        \T_FOR => 1,
        \T_FOREACH => 1,
        \T_WHILE => 1,
        \T_CASE => 1,
        \T_CATCH => 1,
        \T_BOOLEAN_AND => 1,
        \T_LOGICAL_AND => 1,
        \T_BOOLEAN_OR => 1,
        \T_LOGICAL_OR => 1,
        \T_COALESCE => 1,
    ];

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Assess the files cyclomatic complexity.
     *
     * @param string $contents The contents of a PHP file.
     */
    public function assess(string $contents): int
    {
        $tokens = \token_get_all($contents);
        $score = 0;
        foreach ($tokens as $token) {
            if (\is_array($token)) {
                $score += $this->getComplexity($token[0]);

                continue;
            }
            if ('?' !== $token) {
                continue;
            }

            $score += 1;
        }

        return 0 === $score
            ? 1
            : $score;
    }

    /**
     * Add missing tokens depending on the PHP version.
     */
    private function init(): void
    {
        $tokens = [
            // Since PHP 7.4
            'T_COALESCE_EQUAL',
            // Since PHP 8.1
            'T_ENUM',
        ];
        foreach ($tokens as $token) {
            if (!\defined($token)) {
                continue;
            }

            $this->tokens[(int) \constant($token)] = 1;
        }
    }

    /**
     * @param integer $code Code of a PHP token.
     */
    private function getComplexity(int $code): int
    {
        return $this->tokens[$code] ?? 0;
    }
}
