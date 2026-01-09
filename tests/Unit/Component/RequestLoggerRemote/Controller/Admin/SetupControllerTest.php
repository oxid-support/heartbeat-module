<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Tests\Unit\Component\RequestLoggerRemote\Controller\Admin;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Dao\ShopConfigurationDaoInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\DataObject\ModuleConfiguration;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\DataObject\ShopConfiguration;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\ContextInterface;
use OxidSupport\LoggingFramework\Component\ApiUser\Service\ApiUserStatusServiceInterface;
use OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Controller\Admin\SetupController;
use OxidSupport\LoggingFramework\Module\Module;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(SetupController::class)]
final class SetupControllerTest extends TestCase
{
    private const SETTING_COMPONENT_ACTIVE = Module::SETTING_REMOTE_ACTIVE;

    public function testTemplateIsCorrectlySet(): void
    {
        $reflection = new \ReflectionClass(SetupController::class);
        $property = $reflection->getProperty('_sThisTemplate');

        $this->assertSame(
            '@oxsloggingframework/admin/loggingframework_remote_setup',
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

        $controller = $this->createControllerWithMocks(moduleSettingService: $moduleSettingService);

        $this->assertSame($expectedValue, $controller->isComponentActive());
    }

    public static function componentActiveDataProvider(): array
    {
        return [
            'component is active' => [true],
            'component is inactive' => [false],
        ];
    }

    public function testToggleComponentFromActiveToInactiveWhenApiUserSetupComplete(): void
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

        $apiUserStatusService = $this->createMock(ApiUserStatusServiceInterface::class);
        $apiUserStatusService
            ->expects($this->once())
            ->method('isSetupComplete')
            ->willReturn(true);

        $controller = $this->createControllerWithMocks(
            moduleSettingService: $moduleSettingService,
            apiUserStatusService: $apiUserStatusService
        );
        $controller->toggleComponent();
    }

    public function testToggleComponentFromInactiveToActiveWhenApiUserSetupComplete(): void
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

        $apiUserStatusService = $this->createMock(ApiUserStatusServiceInterface::class);
        $apiUserStatusService
            ->expects($this->once())
            ->method('isSetupComplete')
            ->willReturn(true);

        $controller = $this->createControllerWithMocks(
            moduleSettingService: $moduleSettingService,
            apiUserStatusService: $apiUserStatusService
        );
        $controller->toggleComponent();
    }

    public function testToggleComponentDoesNothingWhenApiUserSetupNotComplete(): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->expects($this->never())
            ->method('getBoolean');

        $moduleSettingService
            ->expects($this->never())
            ->method('saveBoolean');

        $apiUserStatusService = $this->createMock(ApiUserStatusServiceInterface::class);
        $apiUserStatusService
            ->expects($this->once())
            ->method('isSetupComplete')
            ->willReturn(false);

        $controller = $this->createControllerWithMocks(
            moduleSettingService: $moduleSettingService,
            apiUserStatusService: $apiUserStatusService
        );
        $controller->toggleComponent();
    }

    public function testIsApiUserSetupCompleteReturnsTrueWhenComplete(): void
    {
        $apiUserStatusService = $this->createMock(ApiUserStatusServiceInterface::class);
        $apiUserStatusService
            ->expects($this->once())
            ->method('isSetupComplete')
            ->willReturn(true);

        $controller = $this->createControllerWithMocks(apiUserStatusService: $apiUserStatusService);

        $this->assertTrue($controller->isApiUserSetupComplete());
    }

    public function testIsApiUserSetupCompleteReturnsFalseWhenNotComplete(): void
    {
        $apiUserStatusService = $this->createMock(ApiUserStatusServiceInterface::class);
        $apiUserStatusService
            ->expects($this->once())
            ->method('isSetupComplete')
            ->willReturn(false);

        $controller = $this->createControllerWithMocks(apiUserStatusService: $apiUserStatusService);

        $this->assertFalse($controller->isApiUserSetupComplete());
    }

    public function testIsApiUserSetupCompleteReturnsFalseOnException(): void
    {
        $apiUserStatusService = $this->createMock(ApiUserStatusServiceInterface::class);
        $apiUserStatusService
            ->expects($this->once())
            ->method('isSetupComplete')
            ->willThrowException(new \Exception('Service error'));

        $controller = $this->createControllerWithMocks(apiUserStatusService: $apiUserStatusService);

        $this->assertFalse($controller->isApiUserSetupComplete());
    }

    public function testIsConfigAccessActivatedReturnsTrueWhenActivated(): void
    {
        $controller = $this->createControllerWithModuleActivationState('oe_graphql_configuration_access', true);

        $this->assertTrue($controller->isConfigAccessActivated());
    }

    public function testIsConfigAccessActivatedReturnsFalseWhenNotActivated(): void
    {
        $controller = $this->createControllerWithModuleActivationState('oe_graphql_configuration_access', false);

        $this->assertFalse($controller->isConfigAccessActivated());
    }

    public function testIsConfigAccessActivatedReturnsFalseOnException(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $context
            ->method('getCurrentShopId')
            ->willReturn(1);

        $shopConfigurationDao = $this->createMock(ShopConfigurationDaoInterface::class);
        $shopConfigurationDao
            ->method('get')
            ->willThrowException(new \Exception('Configuration error'));

        $controller = $this->createControllerWithMocks(
            context: $context,
            shopConfigurationDao: $shopConfigurationDao,
        );

        $this->assertFalse($controller->isConfigAccessActivated());
    }

    private function createControllerWithMocks(
        ?ModuleSettingServiceInterface $moduleSettingService = null,
        ?ContextInterface $context = null,
        ?ShopConfigurationDaoInterface $shopConfigurationDao = null,
        ?ApiUserStatusServiceInterface $apiUserStatusService = null,
    ): SetupController {
        $controller = $this->getMockBuilder(SetupController::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getModuleSettingService', 'getContext', 'getShopConfigurationDao', 'getApiUserStatusService'])
            ->getMock();

        $controller
            ->method('getModuleSettingService')
            ->willReturn($moduleSettingService ?? $this->createStub(ModuleSettingServiceInterface::class));

        $controller
            ->method('getContext')
            ->willReturn($context ?? $this->createStub(ContextInterface::class));

        $controller
            ->method('getShopConfigurationDao')
            ->willReturn($shopConfigurationDao ?? $this->createStub(ShopConfigurationDaoInterface::class));

        $controller
            ->method('getApiUserStatusService')
            ->willReturn($apiUserStatusService ?? $this->createStub(ApiUserStatusServiceInterface::class));

        return $controller;
    }

    private function createControllerWithModuleActivationState(
        string $moduleId,
        bool $isActivated
    ): SetupController {
        $moduleConfiguration = $this->createMock(ModuleConfiguration::class);
        $moduleConfiguration
            ->method('isActivated')
            ->willReturn($isActivated);

        $shopConfiguration = $this->createMock(ShopConfiguration::class);
        $shopConfiguration
            ->method('getModuleConfiguration')
            ->with($moduleId)
            ->willReturn($moduleConfiguration);

        $context = $this->createMock(ContextInterface::class);
        $context
            ->method('getCurrentShopId')
            ->willReturn(1);

        $shopConfigurationDao = $this->createMock(ShopConfigurationDaoInterface::class);
        $shopConfigurationDao
            ->method('get')
            ->with(1)
            ->willReturn($shopConfiguration);

        return $this->createControllerWithMocks(
            context: $context,
            shopConfigurationDao: $shopConfigurationDao,
        );
    }
}
