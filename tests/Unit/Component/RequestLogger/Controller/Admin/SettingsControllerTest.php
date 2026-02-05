<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\RequestLogger\Controller\Admin;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidSupport\Heartbeat\Component\ApiUser\Service\ApiUserStatusServiceInterface;
use OxidSupport\Heartbeat\Component\RequestLogger\Controller\Admin\SettingsController;
use OxidSupport\Heartbeat\Module\Module;
use OxidSupport\Heartbeat\Shared\Controller\Admin\ComponentControllerInterface;
use OxidSupport\Heartbeat\Shared\Controller\Admin\TogglableComponentInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(SettingsController::class)]
final class SettingsControllerTest extends TestCase
{
    private const SETTING_COMPONENT_ACTIVE = Module::SETTING_REQUESTLOGGER_ACTIVE;

    public function testTemplateIsCorrectlySet(): void
    {
        $reflection = new \ReflectionClass(SettingsController::class);
        $property = $reflection->getProperty('_sThisTemplate');

        $this->assertSame(
            '@oxsheartbeat/admin/heartbeat_requestlogger_settings',
            $property->getDefaultValue()
        );
    }

    #[DataProvider('componentActiveDataProvider')]
    public function testIsComponentActiveReturnsCorrectValue(bool $expectedValue): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->expects($this->once())
            ->method('getBoolean')
            ->with(self::SETTING_COMPONENT_ACTIVE, Module::ID)
            ->willReturn($expectedValue);

        $controller = $this->createControllerWithMockedService($moduleSettingService);

        $this->assertSame($expectedValue, $controller->isComponentActive());
    }

    public static function componentActiveDataProvider(): array
    {
        return [
            'component is active' => [true],
            'component is inactive' => [false],
        ];
    }

    public function testToggleComponentFromActiveToInactive(): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->expects($this->once())
            ->method('getBoolean')
            ->with(self::SETTING_COMPONENT_ACTIVE, Module::ID)
            ->willReturn(true);

        $moduleSettingService
            ->expects($this->once())
            ->method('saveBoolean')
            ->with(self::SETTING_COMPONENT_ACTIVE, false, Module::ID);

        $controller = $this->createControllerWithMockedServices($moduleSettingService, true);
        $controller->toggleComponent();
    }

    public function testToggleComponentFromInactiveToActive(): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->expects($this->once())
            ->method('getBoolean')
            ->with(self::SETTING_COMPONENT_ACTIVE, Module::ID)
            ->willReturn(false);

        $moduleSettingService
            ->expects($this->once())
            ->method('saveBoolean')
            ->with(self::SETTING_COMPONENT_ACTIVE, true, Module::ID);

        $controller = $this->createControllerWithMockedServices($moduleSettingService, true);
        $controller->toggleComponent();
    }

    public function testToggleComponentDoesNothingWhenApiUserNotSetup(): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->expects($this->never())
            ->method('saveBoolean');

        $controller = $this->createControllerWithMockedServices($moduleSettingService, false);
        $controller->toggleComponent();
    }

    public function testGetSettingsReturnsCorrectArray(): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);

        $moduleSettingService
            ->method('getBoolean')
            ->willReturnMap([
                [self::SETTING_COMPONENT_ACTIVE, Module::ID, true],
                [Module::SETTING_REQUESTLOGGER_LOG_FRONTEND, Module::ID, true],
                [Module::SETTING_REQUESTLOGGER_LOG_ADMIN, Module::ID, false],
                [Module::SETTING_REQUESTLOGGER_REDACT_ALL_VALUES, Module::ID, false],
            ]);

        $moduleSettingService
            ->method('getString')
            ->with(Module::SETTING_REQUESTLOGGER_LOG_LEVEL, Module::ID)
            ->willReturn(new \Symfony\Component\String\UnicodeString('detailed'));

        $moduleSettingService
            ->method('getCollection')
            ->with(Module::SETTING_REQUESTLOGGER_REDACT_FIELDS, Module::ID)
            ->willReturn(['password', 'secret']);

        $controller = $this->createControllerWithMockedService($moduleSettingService);
        $settings = $controller->getSettings();

        $this->assertTrue($settings['componentActive']);
        $this->assertSame('detailed', $settings['logLevel']);
        $this->assertTrue($settings['logFrontend']);
        $this->assertFalse($settings['logAdmin']);
        $this->assertFalse($settings['redactAllValues']);
        $this->assertSame(['password', 'secret'], $settings['redactFields']);
    }

    public function testCanToggleReturnsTrueWhenApiUserSetupComplete(): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $controller = $this->createControllerWithMockedServices($moduleSettingService, true);

        $this->assertTrue($controller->canToggle());
    }

    public function testCanToggleReturnsFalseWhenApiUserNotSetup(): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $controller = $this->createControllerWithMockedServices($moduleSettingService, false);

        $this->assertFalse($controller->canToggle());
    }

    public function testImplementsTogglableComponentInterface(): void
    {
        $this->assertTrue(
            is_subclass_of(SettingsController::class, TogglableComponentInterface::class)
        );
    }

    public function testImplementsComponentControllerInterface(): void
    {
        $this->assertTrue(
            is_subclass_of(SettingsController::class, ComponentControllerInterface::class)
        );
    }

    #[DataProvider('statusClassDataProvider')]
    public function testGetStatusClassReturnsCorrectValue(bool $isActive, bool $apiUserSetup, string $expectedClass): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->method('getBoolean')
            ->with(self::SETTING_COMPONENT_ACTIVE, Module::ID)
            ->willReturn($isActive);

        $controller = $this->createControllerWithMockedServices($moduleSettingService, $apiUserSetup);

        $this->assertSame($expectedClass, $controller->getStatusClass());
    }

    public static function statusClassDataProvider(): array
    {
        return [
            'api user not setup returns warning' => [true, false, 'warning'],
            'api user setup and active returns active class' => [true, true, 'active'],
            'api user setup and inactive returns inactive class' => [false, true, 'inactive'],
        ];
    }

    #[DataProvider('statusTextKeyDataProvider')]
    public function testGetStatusTextKeyReturnsCorrectValue(bool $isActive, bool $apiUserSetup, string $expectedKey): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->method('getBoolean')
            ->with(self::SETTING_COMPONENT_ACTIVE, Module::ID)
            ->willReturn($isActive);

        $controller = $this->createControllerWithMockedServices($moduleSettingService, $apiUserSetup);

        $this->assertSame($expectedKey, $controller->getStatusTextKey());
    }

    public static function statusTextKeyDataProvider(): array
    {
        return [
            'api user not setup returns warning key' => [true, false, 'OXSHEARTBEAT_REQUESTLOGGER_STATUS_WARNING'],
            'api user setup and active returns active key' => [true, true, 'OXSHEARTBEAT_LF_STATUS_ACTIVE'],
            'api user setup and inactive returns inactive key' => [false, true, 'OXSHEARTBEAT_LF_STATUS_INACTIVE'],
        ];
    }

    private function createControllerWithMockedService(
        ModuleSettingServiceInterface $moduleSettingService
    ): SettingsController {
        return $this->createControllerWithMockedServices($moduleSettingService, true);
    }

    private function createControllerWithMockedServices(
        ModuleSettingServiceInterface $moduleSettingService,
        bool $apiUserSetupComplete
    ): SettingsController {
        $apiUserStatusService = $this->createMock(ApiUserStatusServiceInterface::class);
        $apiUserStatusService
            ->method('isSetupComplete')
            ->willReturn($apiUserSetupComplete);

        $controller = $this->getMockBuilder(SettingsController::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getModuleSettingService', 'getApiUserStatusService'])
            ->getMock();

        $controller
            ->method('getModuleSettingService')
            ->willReturn($moduleSettingService);

        $controller
            ->method('getApiUserStatusService')
            ->willReturn($apiUserStatusService);

        return $controller;
    }
}
