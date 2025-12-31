<?php

/**
 * Used by PHPStan.
 *
 * @see https://github.com/phpstan/phpstan-symfony#analysis-of-symfony-console-commands
 */

declare(strict_types=1);

require_once __DIR__ . '/../bin/bootstrap.php';

use Churn\Command\AssessComplexityCommand;
use Churn\Command\RunCommand;
use Symfony\Component\Console\Application;

$application = new Application('churn-php', 'test');
$method = method_exists($application, 'addCommand')
    ? 'addCommand'
    : 'add';
$application->{$method}(AssessComplexityCommand::newInstance());
$application->{$method}(RunCommand::newInstance());

return $application;
