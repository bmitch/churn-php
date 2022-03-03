<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', 'stderr');

foreach (
    [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php',
    ] as $autoload
) {
    if (is_file($autoload)) {
        return require_once $autoload;
    }
}

fwrite(
    STDERR,
    'You must set up the project dependencies first. Run the following command:' . PHP_EOL .
    'composer install' . PHP_EOL
);
exit(1);
