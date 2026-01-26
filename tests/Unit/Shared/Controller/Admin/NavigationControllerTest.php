<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Shared\Controller\Admin;

// Define the parent class in the same namespace as the controller for testing
if (!class_exists(NavigationController_parent::class)) {
    abstract class NavigationController_parent
    {
        /** @var array<string, mixed> */
        protected array $_aViewData = [];

        public function render()
        {
            return 'navigation.html.twig';
        }
    }
}

namespace OxidSupport\Heartbeat\Tests\Unit\Shared\Controller\Admin;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidSupport\Heartbeat\Component\ApiUser\Service\ApiUserStatusServiceInterface;
use OxidSupport\Heartbeat\Shared\Controller\Admin\NavigationController;
use OxidSupport\Heartbeat\Module\Module;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(NavigationController::class)]
final class NavigationControllerTest extends TestCase
{
    private const SETTING_REQUESTLOGGER_ACTIVE = Module::SETTING_REQUESTLOGGER_ACTIVE;
    private const SETTING_REMOTE_ACTIVE = Module::SETTING_REMOTE_ACTIVE;

    #[DataProvider('componentStatusDataProvider')]
    public function testGetHeartbeatComponentStatusReturnsCorrectValues(
        bool $apiUserSetupComplete,
        bool $requestLoggerActive,
        bool $remoteActive
    ): void {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->method('getBoolean')
            ->willReturnMap([
                [self::SETTING_REQUESTLOGGER_ACTIVE, Module::ID, $requestLoggerActive],
                [self::SETTING_REMOTE_ACTIVE, Module::ID, $remoteActive],
            ]);

        $apiUserStatusService = $this->createMock(ApiUserStatusServiceInterface::class);
        $apiUserStatusService
            ->method('isSetupComplete')
            ->willReturn($apiUserSetupComplete);

        $controller = $this->createControllerWithMocks($moduleSettingService, $apiUserStatusService);
        $status = $controller->getHeartbeatComponentStatus();

        $this->assertSame(
            $apiUserSetupComplete,
            $status['heartbeat_apiuser_setup']
        );
        $this->assertSame(
            $requestLoggerActive,
            $status['heartbeat_requestlogger_settings']
        );
        // heartbeat_remote_setup requires both apiUserSetupComplete AND remoteActive
        $this->assertSame(
            $apiUserSetupComplete && $remoteActive,
            $status['heartbeat_remote_setup']
        );
    }

    public static function componentStatusDataProvider(): array
    {
        return [
            'all active' => [true, true, true],
            'all inactive' => [false, false, false],
            'only api user complete' => [true, false, false],
            'only request logger active' => [false, true, false],
            'only remote active' => [false, false, true],
            'api user and request logger' => [true, true, false],
            'api user and remote' => [true, false, true],
            'request logger and remote' => [false, true, true],
        ];
    }

    public function testRenderAddsComponentStatusToViewData(): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->method('getBoolean')
            ->willReturnMap([
                [self::SETTING_REQUESTLOGGER_ACTIVE, Module::ID, true],
                [self::SETTING_REMOTE_ACTIVE, Module::ID, false],
            ]);

        $apiUserStatusService = $this->createMock(ApiUserStatusServiceInterface::class);
        $apiUserStatusService
            ->method('isSetupComplete')
            ->willReturn(true);

        $controller = $this->createControllerWithMocks($moduleSettingService, $apiUserStatusService);
        $template = $controller->render();

        $this->assertSame('navigation.html.twig', $template);

        $reflection = new \ReflectionClass($controller);
        $property = $reflection->getProperty('_aViewData');
        $property->setAccessible(true);
        $viewData = $property->getValue($controller);

        $this->assertArrayHasKey('lfComponentStatus', $viewData);
        $this->assertTrue($viewData['lfComponentStatus']['heartbeat_apiuser_setup']);
        $this->assertTrue($viewData['lfComponentStatus']['heartbeat_requestlogger_settings']);
        $this->assertFalse($viewData['lfComponentStatus']['heartbeat_remote_setup']);
    }

    public function testApiUserStatusReturnsFalseOnException(): void
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->method('getBoolean')
            ->willReturn(false);

        $apiUserStatusService = $this->createMock(ApiUserStatusServiceInterface::class);
        $apiUserStatusService
            ->method('isSetupComplete')
            ->willThrowException(new \Exception('Service error'));

        $controller = $this->createControllerWithMocks($moduleSettingService, $apiUserStatusService);
        $status = $controller->getHeartbeatComponentStatus();

        $this->assertFalse($status['heartbeat_apiuser_setup']);
    }

    private function createControllerWithMocks(
        ModuleSettingServiceInterface $moduleSettingService,
        ?ApiUserStatusServiceInterface $apiUserStatusService = null
    ): NavigationController {
        $controller = $this->getMockBuilder(NavigationController::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getModuleSettingService', 'getApiUserStatusService'])
            ->getMock();

        $controller
            ->method('getModuleSettingService')
            ->willReturn($moduleSettingService);

        $controller
            ->method('getApiUserStatusService')
            ->willReturn($apiUserStatusService ?? $this->createStub(ApiUserStatusServiceInterface::class));

        return $controller;
    }
}
