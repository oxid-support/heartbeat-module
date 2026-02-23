<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\LogSender\Service;

use OxidSupport\Heartbeat\Component\LogSender\DataType\LogPath;
use OxidSupport\Heartbeat\Component\LogSender\DataType\LogPathType;
use OxidSupport\Heartbeat\Component\LogSender\Service\LogPathProviderInterface;
use OxidSupport\Heartbeat\Component\LogSender\Service\OxidCoreLogPathProvider;
use OxidSupport\Heartbeat\Shop\Facade\ShopFacadeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(OxidCoreLogPathProvider::class)]
final class OxidCoreLogPathProviderTest extends TestCase
{
    public function testImplementsLogPathProviderInterface(): void
    {
        $shopFacade = $this->createMock(ShopFacadeInterface::class);
        $provider = new OxidCoreLogPathProvider($shopFacade);

        $this->assertInstanceOf(LogPathProviderInterface::class, $provider);
    }

    public function testGetLogPathsReturnsArray(): void
    {
        $shopFacade = $this->createMock(ShopFacadeInterface::class);
        $shopFacade->method('getLogsPath')->willReturn('/var/www/log/');

        $provider = new OxidCoreLogPathProvider($shopFacade);

        $this->assertIsArray($provider->getLogPaths());
    }

    public function testGetLogPathsReturnsOneLogPath(): void
    {
        $shopFacade = $this->createMock(ShopFacadeInterface::class);
        $shopFacade->method('getLogsPath')->willReturn('/var/www/log/');

        $provider = new OxidCoreLogPathProvider($shopFacade);

        $this->assertCount(1, $provider->getLogPaths());
    }

    public function testGetLogPathsReturnsLogPathInstance(): void
    {
        $shopFacade = $this->createMock(ShopFacadeInterface::class);
        $shopFacade->method('getLogsPath')->willReturn('/var/www/log/');

        $provider = new OxidCoreLogPathProvider($shopFacade);
        $paths = $provider->getLogPaths();

        $this->assertInstanceOf(LogPath::class, $paths[0]);
    }

    public function testGetLogPathsUsesCorrectFilename(): void
    {
        $shopFacade = $this->createMock(ShopFacadeInterface::class);
        $shopFacade->method('getLogsPath')->willReturn('/var/www/log/');

        $provider = new OxidCoreLogPathProvider($shopFacade);
        $paths = $provider->getLogPaths();

        $this->assertStringEndsWith('oxideshop.log', $paths[0]->path);
    }

    public function testGetLogPathsUsesLogsPathFromShopFacade(): void
    {
        $shopFacade = $this->createMock(ShopFacadeInterface::class);
        $shopFacade->method('getLogsPath')->willReturn('/custom/path/');

        $provider = new OxidCoreLogPathProvider($shopFacade);
        $paths = $provider->getLogPaths();

        $this->assertEquals('/custom/path/oxideshop.log', $paths[0]->path);
    }

    public function testGetLogPathsReturnsFileType(): void
    {
        $shopFacade = $this->createMock(ShopFacadeInterface::class);
        $shopFacade->method('getLogsPath')->willReturn('/var/www/log/');

        $provider = new OxidCoreLogPathProvider($shopFacade);
        $paths = $provider->getLogPaths();

        $this->assertEquals(LogPathType::FILE(), $paths[0]->type);
    }

    public function testGetLogPathsHasCorrectName(): void
    {
        $shopFacade = $this->createMock(ShopFacadeInterface::class);
        $shopFacade->method('getLogsPath')->willReturn('/var/www/log/');

        $provider = new OxidCoreLogPathProvider($shopFacade);
        $paths = $provider->getLogPaths();

        $this->assertEquals('OXID eShop Log', $paths[0]->name);
    }

    public function testGetLogPathsHasDescription(): void
    {
        $shopFacade = $this->createMock(ShopFacadeInterface::class);
        $shopFacade->method('getLogsPath')->willReturn('/var/www/log/');

        $provider = new OxidCoreLogPathProvider($shopFacade);
        $paths = $provider->getLogPaths();

        $this->assertNotEmpty($paths[0]->description);
    }

    public function testGetProviderIdReturnsOxidCore(): void
    {
        $shopFacade = $this->createMock(ShopFacadeInterface::class);
        $provider = new OxidCoreLogPathProvider($shopFacade);

        $this->assertEquals('oxid_core', $provider->getProviderId());
    }

    public function testGetProviderNameReturnsOXIDCore(): void
    {
        $shopFacade = $this->createMock(ShopFacadeInterface::class);
        $provider = new OxidCoreLogPathProvider($shopFacade);

        $this->assertEquals('OXID Core', $provider->getProviderName());
    }

    public function testGetProviderDescriptionReturnsDescription(): void
    {
        $shopFacade = $this->createMock(ShopFacadeInterface::class);
        $provider = new OxidCoreLogPathProvider($shopFacade);

        $this->assertStringContainsString('oxideshop.log', $provider->getProviderDescription());
    }

    public function testIsActiveReturnsTrue(): void
    {
        $shopFacade = $this->createMock(ShopFacadeInterface::class);
        $provider = new OxidCoreLogPathProvider($shopFacade);

        $this->assertTrue($provider->isActive());
    }

    public function testClassIsFinal(): void
    {
        $reflection = new \ReflectionClass(OxidCoreLogPathProvider::class);

        $this->assertTrue($reflection->isFinal());
    }
}
