<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\RequestLogger\Exception;

use GraphQL\Error\ClientAware;
use OxidSupport\Heartbeat\Component\RequestLogger\Exception\InvalidCollectionException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(InvalidCollectionException::class)]
final class InvalidCollectionExceptionTest extends TestCase
{
    public function testImplementsClientAware(): void
    {
        $exception = new InvalidCollectionException('test message');

        $this->assertInstanceOf(ClientAware::class, $exception);
    }

    public function testIsClientSafeReturnsTrue(): void
    {
        $exception = new InvalidCollectionException('test message');

        $this->assertTrue($exception->isClientSafe());
    }

    public function testExtendsException(): void
    {
        $exception = new InvalidCollectionException('test message');

        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function testClassIsFinal(): void
    {
        $reflection = new \ReflectionClass(InvalidCollectionException::class);

        $this->assertTrue($reflection->isFinal());
    }

    public function testExceptionMessageIsPreserved(): void
    {
        $message = 'Invalid collection format';
        $exception = new InvalidCollectionException($message);

        $this->assertEquals($message, $exception->getMessage());
    }

    public function testCanBeThrownAndCaught(): void
    {
        $this->expectException(InvalidCollectionException::class);
        $this->expectExceptionMessage('Test error');

        throw new InvalidCollectionException('Test error');
    }

    public function testExceptionCodeCanBeSet(): void
    {
        $exception = new InvalidCollectionException('message', 42);

        $this->assertEquals(42, $exception->getCode());
    }
}
