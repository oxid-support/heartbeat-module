<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Tests\Unit\Component\RequestLoggerRemote\Exception;

use OxidEsales\GraphQL\Base\Exception\Error;
use OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Exception\RemoteComponentDisabledException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RemoteComponentDisabledException::class)]
final class RemoteComponentDisabledExceptionTest extends TestCase
{
    public function testExtendsGraphQLError(): void
    {
        $exception = new RemoteComponentDisabledException();

        $this->assertInstanceOf(Error::class, $exception);
    }

    public function testHasCorrectMessage(): void
    {
        $exception = new RemoteComponentDisabledException();

        $this->assertSame(
            'Remote component is disabled. Enable it in the admin panel to use the GraphQL API.',
            $exception->getMessage()
        );
    }

    public function testMessageContainsAdminPanelHint(): void
    {
        $exception = new RemoteComponentDisabledException();

        $this->assertStringContainsString('admin panel', $exception->getMessage());
    }

    public function testMessageContainsGraphQLHint(): void
    {
        $exception = new RemoteComponentDisabledException();

        $this->assertStringContainsString('GraphQL API', $exception->getMessage());
    }

    public function testGetCategoryReturnsPermission(): void
    {
        $exception = new RemoteComponentDisabledException();

        $this->assertSame('permission', $exception->getCategory());
    }

    public function testClassIsFinal(): void
    {
        $reflection = new \ReflectionClass(RemoteComponentDisabledException::class);

        $this->assertTrue($reflection->isFinal(), 'Exception class should be final');
    }

    public function testConstructorHasNoParameters(): void
    {
        $reflection = new \ReflectionClass(RemoteComponentDisabledException::class);
        $constructor = $reflection->getConstructor();

        $this->assertNotNull($constructor);
        $this->assertCount(0, $constructor->getParameters(), 'Constructor should have no parameters');
    }

    public function testCanBeThrownAndCaught(): void
    {
        $this->expectException(RemoteComponentDisabledException::class);
        $this->expectExceptionMessage('Remote component is disabled');

        throw new RemoteComponentDisabledException();
    }

    public function testExceptionCodeIsZero(): void
    {
        $exception = new RemoteComponentDisabledException();

        $this->assertSame(0, $exception->getCode());
    }

    public function testExceptionHasNoPreviousException(): void
    {
        $exception = new RemoteComponentDisabledException();

        $this->assertNull($exception->getPrevious());
    }
}
