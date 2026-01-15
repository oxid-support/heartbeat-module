<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Tests\Unit\Component\LogSender\DataType;

use OxidSupport\LoggingFramework\Component\LogSender\DataType\LogPath;
use OxidSupport\LoggingFramework\Component\LogSender\DataType\LogPathType;
use OxidSupport\LoggingFramework\Component\LogSender\DataType\LogSource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogSource::class)]
final class LogSourceTest extends TestCase
{
    private function createTestLogPath(): LogPath
    {
        return new LogPath('/var/log/test.log', LogPathType::FILE, 'Test Log');
    }

    public function testConstructorSetsId(): void
    {
        $source = new LogSource('test_id', 'Test', '', 'static', null, [], true);

        $this->assertEquals('test_id', $source->id);
    }

    public function testConstructorSetsName(): void
    {
        $source = new LogSource('test_id', 'Test Name', '', 'static', null, [], true);

        $this->assertEquals('Test Name', $source->name);
    }

    public function testConstructorSetsDescription(): void
    {
        $source = new LogSource('test_id', 'Test', 'A description', 'static', null, [], true);

        $this->assertEquals('A description', $source->description);
    }

    public function testConstructorSetsOrigin(): void
    {
        $source = new LogSource('test_id', 'Test', '', 'provider', 'mymodule', [], true);

        $this->assertEquals('provider', $source->origin);
    }

    public function testConstructorSetsProviderId(): void
    {
        $source = new LogSource('test_id', 'Test', '', 'provider', 'mymodule', [], true);

        $this->assertEquals('mymodule', $source->providerId);
    }

    public function testConstructorSetsPaths(): void
    {
        $path = $this->createTestLogPath();
        $source = new LogSource('test_id', 'Test', '', 'static', null, [$path], true);

        $this->assertCount(1, $source->paths);
        $this->assertSame($path, $source->paths[0]);
    }

    public function testConstructorSetsAvailable(): void
    {
        $source = new LogSource('test_id', 'Test', '', 'static', null, [], true);

        $this->assertTrue($source->available);
    }

    public function testIsStaticReturnsTrueForStaticOrigin(): void
    {
        $source = new LogSource('test_id', 'Test', '', LogSource::ORIGIN_STATIC, null, [], true);

        $this->assertTrue($source->isStatic());
    }

    public function testIsStaticReturnsFalseForProviderOrigin(): void
    {
        $source = new LogSource('test_id', 'Test', '', LogSource::ORIGIN_PROVIDER, 'mymodule', [], true);

        $this->assertFalse($source->isStatic());
    }

    public function testIsFromProviderReturnsTrueForProviderOrigin(): void
    {
        $source = new LogSource('test_id', 'Test', '', LogSource::ORIGIN_PROVIDER, 'mymodule', [], true);

        $this->assertTrue($source->isFromProvider());
    }

    public function testIsFromProviderReturnsFalseForStaticOrigin(): void
    {
        $source = new LogSource('test_id', 'Test', '', LogSource::ORIGIN_STATIC, null, [], true);

        $this->assertFalse($source->isFromProvider());
    }

    public function testGetFirstPathReturnsFirstPath(): void
    {
        $path1 = $this->createTestLogPath();
        $path2 = new LogPath('/var/log/other.log', LogPathType::FILE, 'Other');
        $source = new LogSource('test_id', 'Test', '', 'static', null, [$path1, $path2], true);

        $this->assertSame($path1, $source->getFirstPath());
    }

    public function testGetFirstPathReturnsNullForEmptyPaths(): void
    {
        $source = new LogSource('test_id', 'Test', '', 'static', null, [], true);

        $this->assertNull($source->getFirstPath());
    }

    public function testGetPathAtReturnsCorrectPath(): void
    {
        $path1 = $this->createTestLogPath();
        $path2 = new LogPath('/var/log/other.log', LogPathType::FILE, 'Other');
        $source = new LogSource('test_id', 'Test', '', 'static', null, [$path1, $path2], true);

        $this->assertSame($path2, $source->getPathAt(1));
    }

    public function testGetPathAtReturnsNullForInvalidIndex(): void
    {
        $source = new LogSource('test_id', 'Test', '', 'static', null, [], true);

        $this->assertNull($source->getPathAt(999));
    }

    public function testGetPathCountReturnsCorrectCount(): void
    {
        $path1 = $this->createTestLogPath();
        $path2 = new LogPath('/var/log/other.log', LogPathType::FILE, 'Other');
        $source = new LogSource('test_id', 'Test', '', 'static', null, [$path1, $path2], true);

        $this->assertEquals(2, $source->getPathCount());
    }

    public function testGetPathCountReturnsZeroForEmptyPaths(): void
    {
        $source = new LogSource('test_id', 'Test', '', 'static', null, [], true);

        $this->assertEquals(0, $source->getPathCount());
    }

    public function testToArrayReturnsCorrectStructure(): void
    {
        $path = $this->createTestLogPath();
        $source = new LogSource('test_id', 'Test Name', 'Desc', 'provider', 'mymodule', [$path], true);

        $array = $source->toArray();

        $this->assertEquals('test_id', $array['id']);
        $this->assertEquals('Test Name', $array['name']);
        $this->assertEquals('Desc', $array['description']);
        $this->assertEquals('provider', $array['origin']);
        $this->assertEquals('mymodule', $array['providerId']);
        $this->assertTrue($array['available']);
        $this->assertCount(1, $array['paths']);
        $this->assertEquals('/var/log/test.log', $array['paths'][0]['path']);
    }

    public function testOriginStaticConstantHasCorrectValue(): void
    {
        $this->assertEquals('static', LogSource::ORIGIN_STATIC);
    }

    public function testOriginProviderConstantHasCorrectValue(): void
    {
        $this->assertEquals('provider', LogSource::ORIGIN_PROVIDER);
    }

    public function testClassIsFinal(): void
    {
        $reflection = new \ReflectionClass(LogSource::class);

        $this->assertTrue($reflection->isFinal());
    }

    public function testAllPropertiesAreReadonly(): void
    {
        $reflection = new \ReflectionClass(LogSource::class);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $this->assertTrue($property->isReadOnly(), "Property {$property->getName()} should be readonly");
        }
    }
}
