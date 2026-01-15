<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Tests\Unit\Component\LogSender\Service;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidSupport\LoggingFramework\Component\LogSender\Service\LogSenderStatusService;
use OxidSupport\LoggingFramework\Component\LogSender\Service\LogSenderStatusServiceInterface;
use OxidSupport\LoggingFramework\Module\Module;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogSenderStatusService::class)]
final class LogSenderStatusServiceTest extends TestCase
{
    private ModuleSettingServiceInterface&MockObject $moduleSettingService;
    private LogSenderStatusService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $this->service = new LogSenderStatusService($this->moduleSettingService);
    }

    // isActive() tests

    public function testIsActiveReturnsTrueWhenSettingIsTrue(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('getBoolean')
            ->with(Module::SETTING_LOGSENDER_ACTIVE, Module::ID)
            ->willReturn(true);

        $this->assertTrue($this->service->isActive());
    }

    public function testIsActiveReturnsFalseWhenSettingIsFalse(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('getBoolean')
            ->with(Module::SETTING_LOGSENDER_ACTIVE, Module::ID)
            ->willReturn(false);

        $this->assertFalse($this->service->isActive());
    }

    public function testIsActiveReturnsFalseOnException(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('getBoolean')
            ->willThrowException(new \RuntimeException('Setting not found'));

        $this->assertFalse($this->service->isActive());
    }

    // getMaxBytes() tests

    public function testGetMaxBytesReturnsConfiguredValue(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('getInteger')
            ->with(Module::SETTING_LOGSENDER_MAX_BYTES, Module::ID)
            ->willReturn(2097152); // 2 MB

        $this->assertEquals(2097152, $this->service->getMaxBytes());
    }

    public function testGetMaxBytesReturnsDefaultOnException(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('getInteger')
            ->willThrowException(new \RuntimeException('Setting not found'));

        $this->assertEquals(1048576, $this->service->getMaxBytes()); // Default 1 MB
    }

    public function testGetMaxBytesReturnsDefaultForZeroValue(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('getInteger')
            ->willReturn(0);

        $this->assertEquals(1048576, $this->service->getMaxBytes()); // Default 1 MB
    }

    public function testGetMaxBytesReturnsDefaultForNegativeValue(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('getInteger')
            ->willReturn(-100);

        $this->assertEquals(1048576, $this->service->getMaxBytes()); // Default 1 MB
    }

    // Service class tests

    public function testServiceImplementsInterface(): void
    {
        $this->assertInstanceOf(LogSenderStatusServiceInterface::class, $this->service);
    }

    public function testClassIsFinalAndReadonly(): void
    {
        $reflection = new \ReflectionClass(LogSenderStatusService::class);

        $this->assertTrue($reflection->isFinal());
        $this->assertTrue($reflection->isReadOnly());
    }
}
