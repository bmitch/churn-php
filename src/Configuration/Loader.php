<?php

declare(strict_types=1);

namespace Churn\Configuration;

use Churn\Configuration\Validator\CachePath;
use Churn\Configuration\Validator\CommitsSince;
use Churn\Configuration\Validator\DirectoriesToScan;
use Churn\Configuration\Validator\FileExtensions;
use Churn\Configuration\Validator\FilesToIgnore;
use Churn\Configuration\Validator\FilesToShow;
use Churn\Configuration\Validator\Hooks;
use Churn\Configuration\Validator\MaxScoreThreshold;
use Churn\Configuration\Validator\MinScoreToShow;
use Churn\Configuration\Validator\ParallelJobs;
use Churn\Configuration\Validator\Vcs;
use InvalidArgumentException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * @internal
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
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
        $confPath = self::normalizePath($originalConfPath = $confPath);

        if (false !== $confPath && \is_readable($confPath)) {
            $config = new EditableConfig($confPath);
            $config->setUnrecognizedKeys(self::validate($config, self::loadYaml($confPath)));

            return $config;
        }

        if ($isDefaultValue) {
            return new Config();
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
        try {
            $content = Yaml::parse((string) \file_get_contents($confPath)) ?? [];
        } catch (ParseException $e) {
            $content = null;
        }

        if (!\is_array($content)) {
            throw new InvalidArgumentException('The content of the configuration file is invalid');
        }

        return $content;
    }

    /**
     * @param EditableConfig $config The configuration object.
     * @param array<mixed> $configuration The array containing the configuration values.
     * @return array<int|string>
     */
    private static function validate(EditableConfig $config, array $configuration): array
    {
        $validators = [new CachePath(), new CommitsSince(), new DirectoriesToScan(), new FileExtensions(),
        new FilesToIgnore(), new FilesToShow(), new Hooks(), new MaxScoreThreshold(), new MinScoreToShow(),
        new ParallelJobs(), new Vcs()];
        foreach ($validators as $validator) {
            $validator->validate($config, $configuration);
            unset($configuration[$validator->getKey()]);
        }

        return \array_keys($configuration);
    }
}
