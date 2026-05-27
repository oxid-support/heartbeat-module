<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\LogSender\DataType;

use OxidSupport\Heartbeat\Component\LogSender\DataType\LogPathInfoType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogPathInfoType::class)]
final class LogPathInfoTypeTest extends TestCase
{
    public function testGetPathReturnsCorrectValue(): void
    {
        $type = new LogPathInfoType(
            '/var/log/test.log',
            'file',
            'Test Log',
            'A test log file',
            true,
            true
        );

        $this->assertEquals('/var/log/test.log', $type->getPath());
    }

    public function testGetTypeReturnsCorrectValue(): void
    {
        $type = new LogPathInfoType(
            '/var/log/test.log',
            'file',
            'Test Log',
            'A test log file',
            true,
            true
        );

        $this->assertEquals('file', $type->getType());
    }

    public function testGetTypeReturnsDirectoryForDirectory(): void
    {
        $type = new LogPathInfoType(
            '/var/log/',
            'directory',
            'Log Directory',
            'A log directory',
            true,
            true
        );

        $this->assertEquals('directory', $type->getType());
    }

    public function testGetNameReturnsCorrectValue(): void
    {
        $type = new LogPathInfoType(
            '/var/log/test.log',
            'file',
            'Custom Name',
            'A test log file',
            true,
            true
        );

        $this->assertEquals('Custom Name', $type->getName());
    }

    public function testGetDescriptionReturnsCorrectValue(): void
    {
        $type = new LogPathInfoType(
            '/var/log/test.log',
            'file',
            'Test Log',
            'Custom description here',
            true,
            true
        );

        $this->assertEquals('Custom description here', $type->getDescription());
    }

    public function testIsExistsReturnsTrueWhenExists(): void
    {
        $type = new LogPathInfoType(
            '/var/log/test.log',
            'file',
            'Test Log',
            'A test log file',
            true,
            true
        );

        $this->assertTrue($type->isExists());
    }

    public function testIsExistsReturnsFalseWhenNotExists(): void
    {
        $type = new LogPathInfoType(
            '/var/log/nonexistent.log',
            'file',
            'Test Log',
            'A test log file',
            false,
            false
        );

        $this->assertFalse($type->isExists());
    }

    public function testIsReadableReturnsTrueWhenReadable(): void
    {
        $type = new LogPathInfoType(
            '/var/log/test.log',
            'file',
            'Test Log',
            'A test log file',
            true,
            true
        );

        $this->assertTrue($type->isReadable());
    }

    public function testIsReadableReturnsFalseWhenNotReadable(): void
    {
        $type = new LogPathInfoType(
            '/var/log/test.log',
            'file',
            'Test Log',
            'A test log file',
            true,
            false
        );

        $this->assertFalse($type->isReadable());
    }

    public function testClassIsFinal(): void
    {
        $reflection = new \ReflectionClass(LogPathInfoType::class);

        $this->assertTrue($reflection->isFinal());
    }

    public function testHasTypeAnnotation(): void
    {
        $reflection = new \ReflectionClass(LogPathInfoType::class);

        $this->assertStringContainsString('@Type', $reflection->getDocComment());
    }

    public function testAllGettersHaveFieldAnnotations(): void
    {
        $reflection = new \ReflectionClass(LogPathInfoType::class);
        $getters = ['getPath', 'getType', 'getName', 'getDescription', 'isExists', 'isReadable'];

        foreach ($getters as $getter) {
            $method = $reflection->getMethod($getter);

            $this->assertStringContainsString(
                '@Field',
                $method->getDocComment(),
                "Method $getter should have @Field annotation"
            );
        }
    }
}
