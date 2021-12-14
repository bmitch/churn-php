<?php

declare(strict_types=1);

namespace Churn\Configuration;

use InvalidArgumentException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * @internal
 */
class Loader
{
    /**
     * @param string $confPath Path of the configuration file to load.
     * @param boolean $isDefaultValue Indicates whether $confPath contains the default value.
     * @throws InvalidArgumentException If the configuration file cannot be loaded.
     */
    public static function fromPath(string $confPath, bool $isDefaultValue): Config
    {
        $originalConfPath = $confPath;
        $confPath = self::normalizePath($confPath);

        if (false !== $confPath && \is_readable($confPath)) {
            $yaml = self::loadYaml($confPath);

            return Config::create($yaml, $confPath);
        }

        if ($isDefaultValue) {
            return Config::createFromDefaultValues();
        }

        throw new InvalidArgumentException('The configuration file can not be read at ' . $originalConfPath);
    }

    /**
     * @param string $confPath Path to normalize.
     * @return string|false
     */
    private static function normalizePath(string $confPath)
    {
        if (\is_dir($confPath)) {
            $confPath = \rtrim($confPath, '/\\') . '/churn.yml';
        }

        return \realpath($confPath);
    }

    /**
     * @param string $confPath Path of the yaml file to load.
     * @return array<mixed>
     * @throws InvalidArgumentException If the configuration file is invalid.
     */
    private static function loadYaml(string $confPath): array
    {
        $content = (string) \file_get_contents($confPath);

        try {
            $yaml = Yaml::parse($content) ?? [];
        } catch (ParseException $e) {
            $yaml = null;
        }

        if (!\is_array($yaml)) {
            throw new InvalidArgumentException('The content of the configuration file is invalid');
        }

        return $yaml;
    }
}
