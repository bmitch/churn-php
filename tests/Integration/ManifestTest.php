<?php

declare(strict_types=1);

namespace Churn\Tests\Integration;

use Churn\Tests\BaseTestCase;
use PharIo\Manifest\ManifestLoader;

class ManifestTest extends BaseTestCase
{
    /** @test */
    public function manifest_is_valid()
    {
        $path = __DIR__ . '/../../manifest.xml';
        $this->assertTrue(is_file($path), 'manifest.xml not found');

        $manifest = ManifestLoader::fromFile($path);

        $name = method_exists($manifest->getName(), 'asString') ? $manifest->getName()->asString() : (string) $manifest->getName();
        $this->assertEquals('bmitch/churn-php', $name);
        $this->assertGreaterThan(0, $manifest->getRequirements()->count());
    }
}
