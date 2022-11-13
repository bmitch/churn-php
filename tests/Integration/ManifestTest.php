<?php

declare(strict_types=1);

namespace Churn\Tests\Integration;

use Churn\Tests\BaseTestCase;
use PharIo\Manifest\ManifestLoader;

class ManifestTest extends BaseTestCase
{
    /** @test */
    public function manifest_is_valid(): void
    {
        $path = __DIR__ . '/../../manifest.xml';
        self::assertTrue(is_file($path), 'manifest.xml not found');

        $manifest = ManifestLoader::fromFile($path);

        // @phpstan-ignore-next-line
        $name = method_exists($manifest->getName(), 'asString') ? $manifest->getName()->asString() : (string) $manifest->getName();
        self::assertSame('bmitch/churn-php', $name);
        self::assertGreaterThan(0, $manifest->getRequirements()->count());
    }
}
