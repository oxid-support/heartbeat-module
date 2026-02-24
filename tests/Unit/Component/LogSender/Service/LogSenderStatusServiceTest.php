<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\LogSender\Service;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleSettingBridgeInterface;
use OxidSupport\Heartbeat\Component\LogSender\Service\LogSenderStatusService;
use OxidSupport\Heartbeat\Component\LogSender\Service\LogSenderStatusServiceInterface;
use OxidSupport\Heartbeat\Module\Module;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogSenderStatusService::class)]
final class LogSenderStatusServiceTest extends TestCase
{
    private ModuleSettingBridgeInterface&MockObject $moduleSettingService;
    private LogSenderStatusService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->moduleSettingService = $this->createMock(ModuleSettingBridgeInterface::class);
        $this->service = new LogSenderStatusService($this->moduleSettingService);
    }

    // isActive() tests

    public function testIsActiveReturnsTrueWhenSettingIsTrue(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('get')
            ->with(Module::SETTING_LOGSENDER_ACTIVE, Module::ID)
            ->willReturn(true);

        $this->assertTrue($this->service->isActive());
    }

    public function testIsActiveReturnsFalseWhenSettingIsFalse(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('get')
            ->with(Module::SETTING_LOGSENDER_ACTIVE, Module::ID)
            ->willReturn(false);

        $this->assertFalse($this->service->isActive());
    }

    public function testIsActiveReturnsFalseOnException(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('get')
            ->willThrowException(new \RuntimeException('Setting not found'));

        $this->assertFalse($this->service->isActive());
    }

    // getMaxBytes() tests

    public function testGetMaxBytesReturnsConfiguredValue(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('get')
            ->with(Module::SETTING_LOGSENDER_MAX_BYTES, Module::ID)
            ->willReturn(2097152); // 2 MB

        $this->assertEquals(2097152, $this->service->getMaxBytes());
    }

    public function testGetMaxBytesReturnsDefaultOnException(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('get')
            ->willThrowException(new \RuntimeException('Setting not found'));

        $this->assertEquals(1048576, $this->service->getMaxBytes()); // Default 1 MB
    }

    public function testGetMaxBytesReturnsDefaultForZeroValue(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('get')
            ->willReturn(0);

        $this->assertEquals(1048576, $this->service->getMaxBytes()); // Default 1 MB
    }

    public function testGetMaxBytesReturnsDefaultForNegativeValue(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('get')
            ->willReturn(-100);

        $this->assertEquals(1048576, $this->service->getMaxBytes()); // Default 1 MB
    }

    // Service class tests

    public function testServiceImplementsInterface(): void
    {
        $this->assertInstanceOf(LogSenderStatusServiceInterface::class, $this->service);
    }

    public function testClassIsFinal(): void
    {
        $reflection = new \ReflectionClass(LogSenderStatusService::class);

        $this->assertTrue($reflection->isFinal());
    }
}
