<?php

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Shop\Facade;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidSupport\Heartbeat\Shop\Facade\ModuleSettingFacade;
use OxidSupport\Heartbeat\Shop\Facade\ModuleSettingFacadeInterface;
use PHPUnit\Framework\TestCase;

class ModuleSettingFacadeTest extends TestCase
{
    private ModuleSettingServiceInterface $moduleSettingService;
    private ModuleSettingFacade $facade;

    protected function setUp(): void
    {
        $this->moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class);
        $this->facade = new ModuleSettingFacade($this->moduleSettingService);
    }

    public function testImplementsInterface(): void
    {
        $this->assertInstanceOf(ModuleSettingFacadeInterface::class, $this->facade);
    }

    public function testGetLogLevelCallsModuleSettingServiceWithCorrectParameters(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('getString')
            ->with('oxsheartbeat_requestlogger_log_level', 'oxsheartbeat')
            ->willReturn(new \Symfony\Component\String\UnicodeString('debug'));

        $result = $this->facade->getLogLevel();

        $this->assertSame('debug', $result);
    }

    public function testGetLogLevelReturnsString(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('getString')
            ->willReturn(new \Symfony\Component\String\UnicodeString('info'));

        $result = $this->facade->getLogLevel();

        $this->assertIsString($result);
    }

    public function testGetLogLevelWithDifferentLevels(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('getString')
            ->willReturn(new \Symfony\Component\String\UnicodeString('warning'));

        $result = $this->facade->getLogLevel();

        $this->assertSame('warning', $result);
    }

    public function testGetRedactItemsCallsModuleSettingServiceWithCorrectParameters(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('getCollection')
            ->with('oxsheartbeat_requestlogger_redact_fields', 'oxsheartbeat')
            ->willReturn(['password', 'token']);

        $result = $this->facade->getRedactItems();

        $this->assertSame(['password', 'token'], $result);
    }

    public function testGetRedactItemsReturnsArray(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('getCollection')
            ->willReturn(['api_key', 'secret']);

        $result = $this->facade->getRedactItems();

        $this->assertIsArray($result);
    }

    public function testGetRedactItemsWithEmptyArray(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('getCollection')
            ->willReturn([]);

        $result = $this->facade->getRedactItems();

        $this->assertSame([], $result);
    }

    public function testGetRedactItemsWithMultipleItems(): void
    {
        $items = ['password', 'token', 'api_key', 'secret', 'auth_token'];

        $this->moduleSettingService
            ->expects($this->once())
            ->method('getCollection')
            ->willReturn($items);

        $result = $this->facade->getRedactItems();

        $this->assertCount(5, $result);
        $this->assertSame($items, $result);
    }

    public function testMultipleCallsToGetLogLevel(): void
    {
        $this->moduleSettingService
            ->expects($this->exactly(3))
            ->method('getString')
            ->willReturnOnConsecutiveCalls(
                new \Symfony\Component\String\UnicodeString('debug'),
                new \Symfony\Component\String\UnicodeString('info'),
                new \Symfony\Component\String\UnicodeString('error')
            );

        $result1 = $this->facade->getLogLevel();
        $result2 = $this->facade->getLogLevel();
        $result3 = $this->facade->getLogLevel();

        $this->assertSame('debug', $result1);
        $this->assertSame('info', $result2);
        $this->assertSame('error', $result3);
    }

    public function testMultipleCallsToGetRedactItems(): void
    {
        $this->moduleSettingService
            ->expects($this->exactly(2))
            ->method('getCollection')
            ->willReturnOnConsecutiveCalls(['password'], ['token', 'secret']);

        $result1 = $this->facade->getRedactItems();
        $result2 = $this->facade->getRedactItems();

        $this->assertSame(['password'], $result1);
        $this->assertSame(['token', 'secret'], $result2);
    }

    public function testGetLogLevelUsesModuleIdInSettingName(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('getString')
            ->with(
                $this->callback(function($arg) {
                    return strpos($arg, 'oxsheartbeat_') === 0;
                }),
                'oxsheartbeat'
            )
            ->willReturn(new \Symfony\Component\String\UnicodeString('info'));

        $this->facade->getLogLevel();
    }

    public function testGetRedactItemsUsesModuleIdInSettingName(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->callback(function($arg) {
                    return strpos($arg, 'oxsheartbeat_') === 0;
                }),
                'oxsheartbeat'
            )
            ->willReturn([]);

        $this->facade->getRedactItems();
    }

    public function testIsRequestLoggerComponentActiveReturnsTrueWhenActive(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('getBoolean')
            ->with('oxsheartbeat_requestlogger_active', 'oxsheartbeat')
            ->willReturn(true);

        $result = $this->facade->isRequestLoggerComponentActive();

        $this->assertTrue($result);
    }

    public function testIsRequestLoggerComponentActiveReturnsFalseWhenInactive(): void
    {
        $this->moduleSettingService
            ->expects($this->once())
            ->method('getBoolean')
            ->with('oxsheartbeat_requestlogger_active', 'oxsheartbeat')
            ->willReturn(false);

        $result = $this->facade->isRequestLoggerComponentActive();

        $this->assertFalse($result);
    }
}
