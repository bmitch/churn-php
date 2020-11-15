<?php

error_reporting(E_ALL);
ini_set('display_errors', 'stderr');

if (is_file($autoload = __DIR__ . '/../vendor/autoload.php')) {
    require_once($autoload);
} elseif (is_file($autoload = __DIR__ . '/../../../autoload.php')) {
    require_once($autoload);
} else {
    fwrite(STDERR,
        'You must set up the project dependencies first. Run the following command:'.PHP_EOL.
        'composer install'.PHP_EOL
    );
    exit(1);
}
