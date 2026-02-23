<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\LogSender\DataType;

use OxidSupport\Heartbeat\Component\LogSender\DataType\LogPath;
use OxidSupport\Heartbeat\Component\LogSender\DataType\LogPathInfoType;
use OxidSupport\Heartbeat\Component\LogSender\DataType\LogPathType;
use OxidSupport\Heartbeat\Component\LogSender\DataType\LogSource;
use OxidSupport\Heartbeat\Component\LogSender\DataType\LogSourceType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogSourceType::class)]
final class LogSourceTypeTest extends TestCase
{
    public function testGetIdReturnsCorrectValue(): void
    {
        $type = new LogSourceType(
            'source-123',
            'Test Source',
            'A test source',
            'static',
            true,
            []
        );

        $this->assertEquals('source-123', $type->getId());
    }

    public function testGetNameReturnsCorrectValue(): void
    {
        $type = new LogSourceType(
            'source-123',
            'Test Source Name',
            'A test source',
            'static',
            true,
            []
        );

        $this->assertEquals('Test Source Name', $type->getName());
    }

    public function testGetDescriptionReturnsCorrectValue(): void
    {
        $type = new LogSourceType(
            'source-123',
            'Test Source',
            'Custom description',
            'static',
            true,
            []
        );

        $this->assertEquals('Custom description', $type->getDescription());
    }

    public function testGetOriginReturnsCorrectValue(): void
    {
        $type = new LogSourceType(
            'source-123',
            'Test Source',
            'A test source',
            'provider',
            true,
            []
        );

        $this->assertEquals('provider', $type->getOrigin());
    }

    public function testIsAvailableReturnsTrueWhenAvailable(): void
    {
        $type = new LogSourceType(
            'source-123',
            'Test Source',
            'A test source',
            'static',
            true,
            []
        );

        $this->assertTrue($type->isAvailable());
    }

    public function testIsAvailableReturnsFalseWhenNotAvailable(): void
    {
        $type = new LogSourceType(
            'source-123',
            'Test Source',
            'A test source',
            'static',
            false,
            []
        );

        $this->assertFalse($type->isAvailable());
    }

    public function testGetPathsReturnsArray(): void
    {
        $type = new LogSourceType(
            'source-123',
            'Test Source',
            'A test source',
            'static',
            true,
            []
        );

        $this->assertIsArray($type->getPaths());
    }

    public function testGetPathsReturnsEmptyArrayWhenNoPaths(): void
    {
        $type = new LogSourceType(
            'source-123',
            'Test Source',
            'A test source',
            'static',
            true,
            []
        );

        $this->assertCount(0, $type->getPaths());
    }

    public function testGetPathsConvertsLogPathsToLogPathInfoTypes(): void
    {
        $logPath = new LogPath(
            '/var/log/test.log',
            LogPathType::FILE(),
            'Test Log',
            'Description'
        );

        $type = new LogSourceType(
            'source-123',
            'Test Source',
            'A test source',
            'static',
            true,
            [$logPath]
        );

        $paths = $type->getPaths();
        $this->assertCount(1, $paths);
        $this->assertInstanceOf(LogPathInfoType::class, $paths[0]);
    }

    public function testFromLogSourceCreatesCorrectInstance(): void
    {
        $logSource = new LogSource(
            'test-id',
            'Test Name',
            'Test Description',
            'static',
            null,
            [],
            true
        );

        $type = LogSourceType::fromLogSource($logSource);

        $this->assertEquals('test-id', $type->getId());
        $this->assertEquals('Test Name', $type->getName());
        $this->assertEquals('Test Description', $type->getDescription());
        $this->assertEquals('static', $type->getOrigin());
        $this->assertTrue($type->isAvailable());
    }

    public function testClassIsFinal(): void
    {
        $reflection = new \ReflectionClass(LogSourceType::class);

        $this->assertTrue($reflection->isFinal());
    }

    public function testHasTypeAttribute(): void
    {
        $reflection = new \ReflectionClass(LogSourceType::class);
        $attributes = $reflection->getAttributes();

        $attributeNames = array_map(fn($a) => $a->getName(), $attributes);
        $this->assertContains('TheCodingMachine\GraphQLite\Annotations\Type', $attributeNames);
    }

    public function testAllGettersHaveFieldAttributes(): void
    {
        $reflection = new \ReflectionClass(LogSourceType::class);
        $getters = ['getId', 'getName', 'getDescription', 'getOrigin', 'isAvailable', 'getPaths'];

        foreach ($getters as $getter) {
            $method = $reflection->getMethod($getter);
            $attributes = $method->getAttributes();
            $attributeNames = array_map(fn($a) => $a->getName(), $attributes);

            $this->assertContains(
                'TheCodingMachine\GraphQLite\Annotations\Field',
                $attributeNames,
                "Method $getter should have Field attribute"
            );
        }
    }

    public function testFromLogSourceIsStaticMethod(): void
    {
        $reflection = new \ReflectionClass(LogSourceType::class);
        $method = $reflection->getMethod('fromLogSource');

        $this->assertTrue($method->isStatic());
    }
}
