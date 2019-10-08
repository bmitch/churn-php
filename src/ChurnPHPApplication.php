<?php declare(strict_types=1);

namespace Churn;

use Symfony\Component\Console\Application;

final class ChurnPHPApplication
{
    const VERSION = '0.5.0';

    public static function create(): Application
    {
        return new Application('churn-php', self::VERSION);
    }
}
