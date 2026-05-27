<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\LogSender\DataType;

use OxidSupport\Heartbeat\Component\LogSender\DataType\LogContentType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogContentType::class)]
final class LogContentTypeTest extends TestCase
{
    public function testGetSourceIdReturnsCorrectValue(): void
    {
        $type = new LogContentType(
            'source-123',
            'Test Source',
            '/var/log/test.log',
            'log content',
            1024,
            1234567890,
            false
        );

        $this->assertEquals('source-123', $type->getSourceId());
    }

    public function testGetSourceNameReturnsCorrectValue(): void
    {
        $type = new LogContentType(
            'source-123',
            'Test Source',
            '/var/log/test.log',
            'log content',
            1024,
            1234567890,
            false
        );

        $this->assertEquals('Test Source', $type->getSourceName());
    }

    public function testGetPathReturnsCorrectValue(): void
    {
        $type = new LogContentType(
            'source-123',
            'Test Source',
            '/var/log/test.log',
            'log content',
            1024,
            1234567890,
            false
        );

        $this->assertEquals('/var/log/test.log', $type->getPath());
    }

    public function testGetContentReturnsCorrectValue(): void
    {
        $type = new LogContentType(
            'source-123',
            'Test Source',
            '/var/log/test.log',
            'log content here',
            1024,
            1234567890,
            false
        );

        $this->assertEquals('log content here', $type->getContent());
    }

    public function testGetSizeReturnsCorrectValue(): void
    {
        $type = new LogContentType(
            'source-123',
            'Test Source',
            '/var/log/test.log',
            'log content',
            2048,
            1234567890,
            false
        );

        $this->assertEquals(2048, $type->getSize());
    }

    public function testGetModifiedReturnsCorrectValue(): void
    {
        $type = new LogContentType(
            'source-123',
            'Test Source',
            '/var/log/test.log',
            'log content',
            1024,
            1609459200,
            false
        );

        $this->assertEquals(1609459200, $type->getModified());
    }

    public function testIsTruncatedReturnsFalseWhenNotTruncated(): void
    {
        $type = new LogContentType(
            'source-123',
            'Test Source',
            '/var/log/test.log',
            'log content',
            1024,
            1234567890,
            false
        );

        $this->assertFalse($type->isTruncated());
    }

    public function testIsTruncatedReturnsTrueWhenTruncated(): void
    {
        $type = new LogContentType(
            'source-123',
            'Test Source',
            '/var/log/test.log',
            '[...truncated...]log content',
            1024,
            1234567890,
            true
        );

        $this->assertTrue($type->isTruncated());
    }

    public function testClassIsFinal(): void
    {
        $reflection = new \ReflectionClass(LogContentType::class);

        $this->assertTrue($reflection->isFinal());
    }

    public function testHasTypeAnnotation(): void
    {
        $reflection = new \ReflectionClass(LogContentType::class);

        $this->assertStringContainsString('@Type', $reflection->getDocComment());
    }

    public function testGetSourceIdHasFieldAnnotation(): void
    {
        $method = (new \ReflectionClass(LogContentType::class))->getMethod('getSourceId');

        $this->assertStringContainsString('@Field', $method->getDocComment());
    }

    public function testAllGettersHaveFieldAnnotations(): void
    {
        $reflection = new \ReflectionClass(LogContentType::class);
        $getters = ['getSourceId', 'getSourceName', 'getPath', 'getContent', 'getSize', 'getModified', 'isTruncated'];

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
