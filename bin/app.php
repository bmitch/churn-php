<?php

declare(strict_types=1);

use Churn\Command\AssessComplexityCommand;
use Churn\Command\RunCommand;
use Composer\InstalledVersions;
use Symfony\Component\Console\Application;

$application = new Application('churn-php', (static function (string $package): string {
    $version = InstalledVersions::getPrettyVersion($package);
    $ref = InstalledVersions::getReference($package);
    if ($ref) {
        $version .= '@' . substr($ref, 0, 7);
    }

    return $version;
})('bmitch/churn-php'));
$method = method_exists($application, 'addCommand')
    ? 'addCommand'
    : 'add';
$application->{$method}(AssessComplexityCommand::newInstance());
$application->{$method}($run = RunCommand::newInstance());
$application->setDefaultCommand($run->getName());

return $application;
