<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Tests\Unit\Component\RequestLoggerRemote\Service;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidSupport\LoggingFramework\Module\Module;
use OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Exception\RemoteComponentDisabledException;
use OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Service\RemoteComponentStatusService;
use OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Service\RemoteComponentStatusServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RemoteComponentStatusService::class)]
final class RemoteComponentStatusServiceTest extends TestCase
{
    public function testImplementsInterface(): void
    {
        $moduleSettingService = $this->createStub(ModuleSettingServiceInterface::class);
        $service = new RemoteComponentStatusService($moduleSettingService);

        $this->assertInstanceOf(RemoteComponentStatusServiceInterface::class, $service);
    }

    public function testIsActiveReturnsTrueWhenSettingIsTrue(): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->expects($this->once())
            ->method('getBoolean')
            ->with(Module::SETTING_REMOTE_ACTIVE, Module::ID)
            ->willReturn(true);

        $service = new RemoteComponentStatusService($moduleSettingService);

        $this->assertTrue($service->isActive());
    }

    public function testIsActiveReturnsFalseWhenSettingIsFalse(): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->expects($this->once())
            ->method('getBoolean')
            ->with(Module::SETTING_REMOTE_ACTIVE, Module::ID)
            ->willReturn(false);

        $service = new RemoteComponentStatusService($moduleSettingService);

        $this->assertFalse($service->isActive());
    }

    public function testAssertComponentActiveDoesNotThrowWhenActive(): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->method('getBoolean')
            ->with(Module::SETTING_REMOTE_ACTIVE, Module::ID)
            ->willReturn(true);

        $service = new RemoteComponentStatusService($moduleSettingService);

        // Should not throw
        $service->assertComponentActive();
        $this->assertTrue(true);
    }

    public function testAssertComponentActiveThrowsWhenInactive(): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->method('getBoolean')
            ->with(Module::SETTING_REMOTE_ACTIVE, Module::ID)
            ->willReturn(false);

        $service = new RemoteComponentStatusService($moduleSettingService);

        $this->expectException(RemoteComponentDisabledException::class);
        $service->assertComponentActive();
    }

    public function testAssertComponentActiveThrowsCorrectExceptionType(): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->method('getBoolean')
            ->willReturn(false);

        $service = new RemoteComponentStatusService($moduleSettingService);

        try {
            $service->assertComponentActive();
            $this->fail('Expected RemoteComponentDisabledException to be thrown');
        } catch (RemoteComponentDisabledException $e) {
            $this->assertStringContainsString('Remote component is disabled', $e->getMessage());
            $this->assertSame('permission', $e->getCategory());
        }
    }

    public function testClassIsFinalAndReadonly(): void
    {
        $reflection = new \ReflectionClass(RemoteComponentStatusService::class);

        $this->assertTrue($reflection->isFinal(), 'Class should be final');
        $this->assertTrue($reflection->isReadOnly(), 'Class should be readonly');
    }

    public function testUsesCorrectSettingConstant(): void
    {
        // Verify the service uses the correct module setting constant
        $this->assertSame('oxsloggingframework_remote_active', Module::SETTING_REMOTE_ACTIVE);
    }
}
