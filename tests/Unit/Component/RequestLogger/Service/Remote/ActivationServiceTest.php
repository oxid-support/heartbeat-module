<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\RequestLogger\Service\Remote;

use OxidEsales\GraphQL\ConfigurationAccess\Module\DataType\BooleanSetting;
use OxidEsales\GraphQL\ConfigurationAccess\Module\Service\ModuleSettingServiceInterface as ConfigAccessSettingService;
use OxidSupport\Heartbeat\Module\Module as RequestLoggerModule;
use OxidSupport\Heartbeat\Component\RequestLogger\Service\Remote\ActivationService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ActivationService::class)]
final class ActivationServiceTest extends TestCase
{
    private const SETTING_ACTIVE = RequestLoggerModule::SETTING_REQUESTLOGGER_ACTIVE;

    protected function setUp(): void
    {
        if (!class_exists(BooleanSetting::class)) {
            $this->markTestSkipped('BooleanSetting class from GraphQL Configuration Access not available');
        }
    }

    public function testActivateReturnsTrue(): void
    {
        $booleanSetting = $this->createMock(BooleanSetting::class);
        $booleanSetting->method('getValue')->willReturn(true);

        $settingService = $this->createMock(ConfigAccessSettingService::class);
        $settingService
            ->expects($this->once())
            ->method('changeBooleanSetting')
            ->with(self::SETTING_ACTIVE, true, RequestLoggerModule::ID)
            ->willReturn($booleanSetting);

        $result = $this->getSut($settingService)->activate();

        $this->assertTrue($result);
    }

    public function testDeactivateReturnsTrue(): void
    {
        $booleanSetting = $this->createMock(BooleanSetting::class);

        $settingService = $this->createMock(ConfigAccessSettingService::class);
        $settingService
            ->expects($this->once())
            ->method('changeBooleanSetting')
            ->with(self::SETTING_ACTIVE, false, RequestLoggerModule::ID)
            ->willReturn($booleanSetting);

        $result = $this->getSut($settingService)->deactivate();

        $this->assertTrue($result);
    }

    public function testIsActiveReturnsTrue(): void
    {
        $booleanSetting = $this->createMock(BooleanSetting::class);
        $booleanSetting->method('getValue')->willReturn(true);

        $settingService = $this->createMock(ConfigAccessSettingService::class);
        $settingService
            ->expects($this->once())
            ->method('getBooleanSetting')
            ->with(self::SETTING_ACTIVE, RequestLoggerModule::ID)
            ->willReturn($booleanSetting);

        $result = $this->getSut($settingService)->isActive();

        $this->assertTrue($result);
    }

    public function testIsActiveReturnsFalse(): void
    {
        $booleanSetting = $this->createMock(BooleanSetting::class);
        $booleanSetting->method('getValue')->willReturn(false);

        $settingService = $this->createMock(ConfigAccessSettingService::class);
        $settingService
            ->expects($this->once())
            ->method('getBooleanSetting')
            ->with(self::SETTING_ACTIVE, RequestLoggerModule::ID)
            ->willReturn($booleanSetting);

        $result = $this->getSut($settingService)->isActive();

        $this->assertFalse($result);
    }

    public function testActivateUsesCorrectSettingName(): void
    {
        $this->assertSame('oxsheartbeat_requestlogger_active', self::SETTING_ACTIVE);
    }

    private function getSut(
        ?ConfigAccessSettingService $settingService = null,
    ): ActivationService {
        return new ActivationService(
            moduleSettingService: $settingService ?? $this->createStub(ConfigAccessSettingService::class),
        );
    }
}
