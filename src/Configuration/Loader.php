<?php

declare(strict_types=1);

namespace Churn\Configuration;

use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

class Loader
{

    /**
     * @param string $confPath Path of the configuration file to load.
     * @throws InvalidArgumentException If the configuration file cannot be read.
     */
    public static function fromPath(string $confPath): Config
    {
        $originalConfPath = $confPath;

        if (\is_dir($confPath)) {
            $confPath = \rtrim($confPath, '/\\') . '/churn.yml';
        }

        if (!\is_readable($confPath)) {
            throw new InvalidArgumentException('The configuration file can not be read at ' . $originalConfPath);
        }

        $content = (string) \file_get_contents($confPath);

        return Config::create(Yaml::parse($content) ?? []);
    }
}
