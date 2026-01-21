<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\DiagnosticsProvider\Exception;

use OxidSupport\Heartbeat\Component\DiagnosticsProvider\Exception\DiagnosticsProviderDisabledException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DiagnosticsProviderDisabledException::class)]
final class DiagnosticsProviderDisabledExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $exception = new DiagnosticsProviderDisabledException();

        $this->assertEquals('Diagnostics Provider component is disabled.', $exception->getMessage());
    }

    public function testExtendsException(): void
    {
        $exception = new DiagnosticsProviderDisabledException();

        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function testClassIsFinal(): void
    {
        $reflection = new \ReflectionClass(DiagnosticsProviderDisabledException::class);

        $this->assertTrue($reflection->isFinal());
    }

    public function testConstructorHasNoParameters(): void
    {
        $reflection = new \ReflectionClass(DiagnosticsProviderDisabledException::class);
        $constructor = $reflection->getConstructor();

        $this->assertNotNull($constructor);
        $this->assertCount(0, $constructor->getParameters());
    }

    public function testCanBeThrownAndCaught(): void
    {
        $this->expectException(DiagnosticsProviderDisabledException::class);

        throw new DiagnosticsProviderDisabledException();
    }

    public function testExceptionCodeIsZero(): void
    {
        $exception = new DiagnosticsProviderDisabledException();

        $this->assertEquals(0, $exception->getCode());
    }
}
