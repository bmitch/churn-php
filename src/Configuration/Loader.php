<?php

declare(strict_types=1);

namespace Churn\Configuration;

use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

/**
 * @internal
 */
class Loader
{
    /**
     * @param string $confPath Path of the configuration file to load.
     * @param boolean $isDefaultValue Indicates whether $confPath contains the default value.
     * @throws InvalidArgumentException If the configuration file cannot be read.
     */
    public static function fromPath(string $confPath, bool $isDefaultValue): Config
    {
        $originalConfPath = $confPath;

        if (\is_dir($confPath)) {
            $confPath = \rtrim($confPath, '/\\') . '/churn.yml';
        }

        if (\is_readable($confPath)) {
            $content = (string) \file_get_contents($confPath);

            return Config::create(Yaml::parse($content) ?? [], \realpath($confPath));
        }

        if ($isDefaultValue) {
            return Config::createFromDefaultValues();
        }

        throw new InvalidArgumentException('The configuration file can not be read at ' . $originalConfPath);
    }
}
