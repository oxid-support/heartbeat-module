<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Tests\Unit\Component\LogSender\Exception;

use OxidSupport\LoggingFramework\Component\LogSender\Exception\LogSourceNotFoundException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogSourceNotFoundException::class)]
final class LogSourceNotFoundExceptionTest extends TestCase
{
    public function testExceptionMessageContainsSourceId(): void
    {
        $sourceId = 'provider_requestlogger';
        $exception = new LogSourceNotFoundException($sourceId);

        $this->assertStringContainsString($sourceId, $exception->getMessage());
    }

    public function testExceptionMessageFormat(): void
    {
        $sourceId = 'static_0';
        $exception = new LogSourceNotFoundException($sourceId);

        $this->assertEquals('Log source not found: static_0', $exception->getMessage());
    }

    public function testExceptionExtendsException(): void
    {
        $exception = new LogSourceNotFoundException('test');

        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function testClassIsFinal(): void
    {
        $reflection = new \ReflectionClass(LogSourceNotFoundException::class);

        $this->assertTrue($reflection->isFinal());
    }
}
