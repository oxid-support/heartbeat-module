<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Tests\Unit\Component\RequestLoggerRemote\Controller\GraphQL;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Controller\GraphQL\ActivationController;
use OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Controller\GraphQL\SettingController;
use OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Exception\RemoteComponentDisabledException;
use OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Service\ActivationServiceInterface;
use OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Service\RemoteComponentStatusService;
use OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Service\RemoteComponentStatusServiceInterface;
use OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Service\SettingServiceInterface;
use OxidSupport\LoggingFramework\Module\Module;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Tests that GraphQL controllers block requests when remote component is disabled.
 *
 * When remote_active = false, all GraphQL endpoints should throw RemoteComponentDisabledException.
 * This allows customers to disable external support access while keeping admin configuration accessible.
 */
#[CoversClass(SettingController::class)]
#[CoversClass(ActivationController::class)]
final class RemoteComponentBlockingTest extends TestCase
{
    // ==========================================
    // SettingController Tests
    // ==========================================

    #[DataProvider('settingControllerQueryMethodsProvider')]
    public function testSettingControllerQueryBlocksWhenDisabled(string $method): void
    {
        $controller = $this->createSettingControllerWithDisabledComponent();

        $this->expectException(RemoteComponentDisabledException::class);
        $controller->$method();
    }

    public static function settingControllerQueryMethodsProvider(): array
    {
        return [
            'requestLoggerSettings' => ['requestLoggerSettings'],
            'requestLoggerLogLevel' => ['requestLoggerLogLevel'],
            'requestLoggerLogFrontend' => ['requestLoggerLogFrontend'],
            'requestLoggerLogAdmin' => ['requestLoggerLogAdmin'],
            'requestLoggerRedact' => ['requestLoggerRedact'],
            'requestLoggerRedactAllValues' => ['requestLoggerRedactAllValues'],
        ];
    }

    public function testSettingControllerLogLevelChangeBlocksWhenDisabled(): void
    {
        $controller = $this->createSettingControllerWithDisabledComponent();

        $this->expectException(RemoteComponentDisabledException::class);
        $controller->requestLoggerLogLevelChange('detailed');
    }

    public function testSettingControllerLogFrontendChangeBlocksWhenDisabled(): void
    {
        $controller = $this->createSettingControllerWithDisabledComponent();

        $this->expectException(RemoteComponentDisabledException::class);
        $controller->requestLoggerLogFrontendChange(true);
    }

    public function testSettingControllerLogAdminChangeBlocksWhenDisabled(): void
    {
        $controller = $this->createSettingControllerWithDisabledComponent();

        $this->expectException(RemoteComponentDisabledException::class);
        $controller->requestLoggerLogAdminChange(true);
    }

    public function testSettingControllerRedactChangeBlocksWhenDisabled(): void
    {
        $controller = $this->createSettingControllerWithDisabledComponent();

        $this->expectException(RemoteComponentDisabledException::class);
        $controller->requestLoggerRedactChange('["password"]');
    }

    public function testSettingControllerRedactAllValuesChangeBlocksWhenDisabled(): void
    {
        $controller = $this->createSettingControllerWithDisabledComponent();

        $this->expectException(RemoteComponentDisabledException::class);
        $controller->requestLoggerRedactAllValuesChange(true);
    }

    // ==========================================
    // ActivationController Tests
    // ==========================================

    public function testActivationControllerIsActiveBlocksWhenDisabled(): void
    {
        $controller = $this->createActivationControllerWithDisabledComponent();

        $this->expectException(RemoteComponentDisabledException::class);
        $controller->requestLoggerIsActive();
    }

    public function testActivationControllerActivateBlocksWhenDisabled(): void
    {
        $controller = $this->createActivationControllerWithDisabledComponent();

        $this->expectException(RemoteComponentDisabledException::class);
        $controller->requestLoggerActivate();
    }

    public function testActivationControllerDeactivateBlocksWhenDisabled(): void
    {
        $controller = $this->createActivationControllerWithDisabledComponent();

        $this->expectException(RemoteComponentDisabledException::class);
        $controller->requestLoggerDeactivate();
    }

    // ==========================================
    // Positive Tests - Component Enabled
    // ==========================================

    public function testSettingControllerAllowsQueriesWhenEnabled(): void
    {
        $settingService = $this->createMock(SettingServiceInterface::class);
        $settingService->method('getLogLevel')->willReturn('standard');

        $controller = new SettingController(
            $settingService,
            $this->createEnabledComponentStatusService()
        );

        // Should not throw
        $result = $controller->requestLoggerLogLevel();
        $this->assertSame('standard', $result);
    }

    public function testActivationControllerAllowsQueriesWhenEnabled(): void
    {
        $activationService = $this->createMock(ActivationServiceInterface::class);
        $activationService->method('isActive')->willReturn(true);

        $controller = new ActivationController(
            $activationService,
            $this->createEnabledComponentStatusService()
        );

        // Should not throw
        $result = $controller->requestLoggerIsActive();
        $this->assertTrue($result);
    }

    // ==========================================
    // Helper Methods
    // ==========================================

    private function createDisabledComponentStatusService(): RemoteComponentStatusServiceInterface
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->method('getBoolean')
            ->with(Module::SETTING_REMOTE_ACTIVE, Module::ID)
            ->willReturn(false);

        return new RemoteComponentStatusService($moduleSettingService);
    }

    private function createEnabledComponentStatusService(): RemoteComponentStatusServiceInterface
    {
        $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingService
            ->method('getBoolean')
            ->with(Module::SETTING_REMOTE_ACTIVE, Module::ID)
            ->willReturn(true);

        return new RemoteComponentStatusService($moduleSettingService);
    }

    private function createSettingControllerWithDisabledComponent(): SettingController
    {
        return new SettingController(
            $this->createStub(SettingServiceInterface::class),
            $this->createDisabledComponentStatusService()
        );
    }

    private function createActivationControllerWithDisabledComponent(): ActivationController
    {
        return new ActivationController(
            $this->createStub(ActivationServiceInterface::class),
            $this->createDisabledComponentStatusService()
        );
    }
}
