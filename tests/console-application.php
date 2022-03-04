<?php

/**
 * Used by PHPStan.
 *
 * @see https://github.com/phpstan/phpstan-symfony#analysis-of-symfony-console-commands
 */

require_once __DIR__ . '/../bin/bootstrap.php';

use Churn\Command\AssessComplexityCommand;
use Churn\Command\RunCommand;
use Symfony\Component\Console\Application;

$application = new Application('churn-php', 'test');
$application->add(AssessComplexityCommand::newInstance());
$application->add(RunCommand::newInstance());

return $application;
