<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\DiagnosticsProvider\Service;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleSettingBridgeInterface;
use OxidSupport\Heartbeat\Component\DiagnosticsProvider\Service\DiagnosticsProviderStatusService;
use OxidSupport\Heartbeat\Component\DiagnosticsProvider\Service\DiagnosticsProviderStatusServiceInterface;
use OxidSupport\Heartbeat\Module\Module;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(DiagnosticsProviderStatusService::class)]
final class DiagnosticsProviderStatusServiceTest extends TestCase
{
    private ModuleSettingBridgeInterface&MockObject $moduleSettingService;
    private DiagnosticsProviderStatusService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->moduleSettingService = $this->createMock(ModuleSettingBridgeInterface::class);
        $this->service = new DiagnosticsProviderStatusService($this->moduleSettingService);
    }

    // isActive() tests

    public function testIsActiveReturnsTrueWhenSettingIsTrue(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('get')
            ->with(Module::SETTING_DIAGNOSTICSPROVIDER_ACTIVE, Module::ID)
            ->willReturn(true);

        $this->assertTrue($this->service->isActive());
    }

    public function testIsActiveReturnsFalseWhenSettingIsFalse(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('get')
            ->with(Module::SETTING_DIAGNOSTICSPROVIDER_ACTIVE, Module::ID)
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

    // Service class tests

    public function testServiceImplementsInterface(): void
    {
        $this->assertInstanceOf(DiagnosticsProviderStatusServiceInterface::class, $this->service);
    }

    public function testClassIsFinal(): void
    {
        $reflection = new \ReflectionClass(DiagnosticsProviderStatusService::class);

        $this->assertTrue($reflection->isFinal());
    }

    public function testConstructorHasOneParameter(): void
    {
        $reflection = new \ReflectionClass(DiagnosticsProviderStatusService::class);
        $constructor = $reflection->getConstructor();

        $this->assertNotNull($constructor);
        $this->assertCount(1, $constructor->getParameters());
    }
}
