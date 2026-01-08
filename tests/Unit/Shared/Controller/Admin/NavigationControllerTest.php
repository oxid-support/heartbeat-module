<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Shared\Controller\Admin;

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

namespace OxidSupport\LoggingFramework\Tests\Unit\Shared\Controller\Admin;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidSupport\LoggingFramework\Shared\Controller\Admin\NavigationController;
use OxidSupport\LoggingFramework\Module\Module;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(NavigationController::class)]
final class NavigationControllerTest extends TestCase
{
    private const SETTING_REQUESTLOGGER_ACTIVE = Module::SETTING_REQUESTLOGGER_ACTIVE;
    private const SETTING_REMOTE_ACTIVE = Module::SETTING_REMOTE_ACTIVE;

    #[DataProvider('componentStatusDataProvider')]
    public function testGetLoggingFrameworkComponentStatusReturnsCorrectValues(
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

        $controller = $this->createControllerWithMock($moduleSettingService);
        $status = $controller->getLoggingFrameworkComponentStatus();

        $this->assertSame(
            $requestLoggerActive,
            $status['loggingframework_requestlogger_settings']
        );
        $this->assertSame(
            $remoteActive,
            $status['loggingframework_remote_setup']
        );
    }

    public static function componentStatusDataProvider(): array
    {
        return [
            'both active' => [true, true],
            'both inactive' => [false, false],
            'only request logger active' => [true, false],
            'only remote active' => [false, true],
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

        $controller = $this->createControllerWithMock($moduleSettingService);
        $template = $controller->render();

        $this->assertSame('navigation.html.twig', $template);

        $reflection = new \ReflectionClass($controller);
        $property = $reflection->getProperty('_aViewData');
        $property->setAccessible(true);
        $viewData = $property->getValue($controller);

        $this->assertArrayHasKey('lfComponentStatus', $viewData);
        $this->assertTrue($viewData['lfComponentStatus']['loggingframework_requestlogger_settings']);
        $this->assertFalse($viewData['lfComponentStatus']['loggingframework_remote_setup']);
    }

    private function createControllerWithMock(
        ModuleSettingServiceInterface $moduleSettingService
    ): NavigationController {
        $controller = $this->getMockBuilder(NavigationController::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getModuleSettingService'])
            ->getMock();

        $controller
            ->method('getModuleSettingService')
            ->willReturn($moduleSettingService);

        return $controller;
    }
}
