<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Tests\Unit\Component\LogSender\Exception;

use OxidSupport\LoggingFramework\Component\LogSender\Exception\LogPathNotFoundException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogPathNotFoundException::class)]
final class LogPathNotFoundExceptionTest extends TestCase
{
    public function testExceptionMessageContainsPath(): void
    {
        $path = '/var/log/test.log';
        $exception = new LogPathNotFoundException($path);

        $this->assertStringContainsString($path, $exception->getMessage());
    }

    public function testExceptionMessageFormat(): void
    {
        $path = '/var/log/test.log';
        $exception = new LogPathNotFoundException($path);

        $this->assertEquals('Log path not found: /var/log/test.log', $exception->getMessage());
    }

    public function testExceptionExtendsException(): void
    {
        $exception = new LogPathNotFoundException('/test');

        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function testClassIsFinal(): void
    {
        $reflection = new \ReflectionClass(LogPathNotFoundException::class);

        $this->assertTrue($reflection->isFinal());
    }
}
