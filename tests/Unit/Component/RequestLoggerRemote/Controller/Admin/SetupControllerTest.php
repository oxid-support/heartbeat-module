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
use OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Controller\Admin\SetupController;
use OxidSupport\LoggingFramework\Module\Module;
use OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Service\SetupStatusServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\UnicodeString;

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

        $controller = $this->createControllerWithMocks(moduleSettingService: $moduleSettingService);
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

        $controller = $this->createControllerWithMocks(moduleSettingService: $moduleSettingService);
        $controller->toggleComponent();
    }

    public function testGetSetupTokenReturnsToken(): void
    {
        $expectedToken = 'abc123token';

        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->expects($this->once())
            ->method('getString')
            ->with(Module::SETTING_REMOTE_SETUP_TOKEN, Module::ID)
            ->willReturn(new UnicodeString($expectedToken));

        $controller = $this->createControllerWithMocks(moduleSettingService: $moduleSettingService);

        $this->assertSame($expectedToken, $controller->getSetupToken());
    }

    public function testGetSetupTokenReturnsEmptyStringWhenNoToken(): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->expects($this->once())
            ->method('getString')
            ->with(Module::SETTING_REMOTE_SETUP_TOKEN, Module::ID)
            ->willReturn(new UnicodeString(''));

        $controller = $this->createControllerWithMocks(moduleSettingService: $moduleSettingService);

        $this->assertSame('', $controller->getSetupToken());
    }

    public function testIsModuleActivatedReturnsTrueWhenActivated(): void
    {
        $controller = $this->createControllerWithModuleActivationState(Module::ID, true);

        $this->assertTrue($controller->isModuleActivated());
    }

    public function testIsModuleActivatedReturnsFalseWhenNotActivated(): void
    {
        $controller = $this->createControllerWithModuleActivationState(Module::ID, false);

        $this->assertFalse($controller->isModuleActivated());
    }

    public function testIsGraphqlBaseActivatedReturnsTrueWhenActivated(): void
    {
        $controller = $this->createControllerWithModuleActivationState('oe_graphql_base', true);

        $this->assertTrue($controller->isGraphqlBaseActivated());
    }

    public function testIsGraphqlBaseActivatedReturnsFalseWhenNotActivated(): void
    {
        $controller = $this->createControllerWithModuleActivationState('oe_graphql_base', false);

        $this->assertFalse($controller->isGraphqlBaseActivated());
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

    public function testIsMigrationExecutedReturnsTrueWhenExecuted(): void
    {
        $setupStatusService = $this->createMock(SetupStatusServiceInterface::class);
        $setupStatusService
            ->expects($this->once())
            ->method('isMigrationExecuted')
            ->willReturn(true);

        $controller = $this->createControllerWithMocks(setupStatusService: $setupStatusService);

        $this->assertTrue($controller->isMigrationExecuted());
    }

    public function testIsMigrationExecutedReturnsFalseWhenNotExecuted(): void
    {
        $setupStatusService = $this->createMock(SetupStatusServiceInterface::class);
        $setupStatusService
            ->expects($this->once())
            ->method('isMigrationExecuted')
            ->willReturn(false);

        $controller = $this->createControllerWithMocks(setupStatusService: $setupStatusService);

        $this->assertFalse($controller->isMigrationExecuted());
    }

    public function testIsMigrationExecutedReturnsFalseOnException(): void
    {
        $setupStatusService = $this->createMock(SetupStatusServiceInterface::class);
        $setupStatusService
            ->expects($this->once())
            ->method('isMigrationExecuted')
            ->willThrowException(new \Exception('Database error'));

        $controller = $this->createControllerWithMocks(setupStatusService: $setupStatusService);

        $this->assertFalse($controller->isMigrationExecuted());
    }

    public function testResetPasswordGeneratesNewToken(): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->expects($this->once())
            ->method('saveString')
            ->with(
                Module::SETTING_REMOTE_SETUP_TOKEN,
                $this->callback(function ($token) {
                    // Token should be 64 hex characters (32 bytes = 64 hex chars)
                    return is_string($token) && strlen($token) === 64 && ctype_xdigit($token);
                }),
                Module::ID
            );

        $controller = $this->createControllerWithMocks(moduleSettingService: $moduleSettingService);
        $controller->resetPassword();
    }

    private function createControllerWithMocks(
        ?ModuleSettingServiceInterface $moduleSettingService = null,
        ?ContextInterface $context = null,
        ?ShopConfigurationDaoInterface $shopConfigurationDao = null,
        ?SetupStatusServiceInterface $setupStatusService = null,
    ): SetupController {
        $controller = $this->getMockBuilder(SetupController::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getModuleSettingService', 'getContext', 'getShopConfigurationDao', 'getSetupStatusService'])
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
            ->method('getSetupStatusService')
            ->willReturn($setupStatusService ?? $this->createStub(SetupStatusServiceInterface::class));

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
