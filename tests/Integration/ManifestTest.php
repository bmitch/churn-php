<?php

declare(strict_types=1);

namespace Churn\Tests\Integration;

use Churn\Tests\BaseTestCase;
use PharIo\Manifest\ManifestLoader;
use PharIo\Manifest\PhpExtensionRequirement;
use RuntimeException;

final class ManifestTest extends BaseTestCase
{
    /**
     * @var \PharIo\Manifest\Manifest
     */
    private $manifest;

    /** @return void */
    #[\Override]
    public function setUp()
    {
        parent::setUp();

        $path = __DIR__ . '/../../manifest.xml';
        self::assertTrue(\is_file($path), 'manifest.xml not found');

        $this->manifest = ManifestLoader::fromFile($path);
    }

    /** @test */
    public function manifest_is_valid(): void
    {
        $name = $this->getStringRepresentation($this->manifest->getName());
        self::assertSame('bmitch/churn-php', $name);
        self::assertGreaterThan(0, $this->manifest->getRequirements()->count());
    }

    /** @test */
    public function manifest_requires_same_extensions_than_composer(): void
    {
        self::assertEqualsCanonicalizing(
            $this->getComposerExtensionRequirements(),
            $this->getManifestExtensionRequirements()
        );
    }

    /**
     * @return array<string>
     */
    private function getManifestExtensionRequirements(): array
    {
        $requirements = [];
        foreach ($this->manifest->getRequirements() as $requirement) {
            if (!$requirement instanceof PhpExtensionRequirement) {
                continue;
            }

            $requirements[] = $this->getStringRepresentation($requirement);
        }

        return $requirements;
    }

    /**
     * @return array<string>
     */
    private function getComposerExtensionRequirements(): array
    {
        $path = __DIR__ . '/../../composer.json';
        self::assertNotFalse($contents = \file_get_contents($path));
        /** @var mixed $json */
        $json = \json_decode($contents, true);
        self::assertIsArray($json);
        self::assertArrayHasKey('require', $json);
        self::assertIsArray($json['require']);

        $requirements = [];
        foreach (\array_keys($json['require']) as $requirement) {
            if (!\is_string($requirement) || 0 !== \strpos($requirement, 'ext-')) {
                continue;
            }

            $requirements[] = \substr($requirement, 4);
        }

        return $requirements;
    }

    /**
     * @param object $object The object containing the string.
     * @throws RuntimeException If the string is not found.
     */
    private function getStringRepresentation($object): string
    {
        $result = null;
        if (\method_exists($object, 'asString')) {
            /** @var mixed $result */
            $result = $object->asString();
        } elseif (\method_exists($object, '__toString')) {
            /** @var mixed $result */
            $result = $object->__toString();
        }
        if (!\is_string($result)) {
            throw new RuntimeException('String representation not found');
        }

        return $result;
    }
}
