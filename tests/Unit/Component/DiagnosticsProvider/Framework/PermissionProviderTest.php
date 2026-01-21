<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\DiagnosticsProvider\Framework;

use OxidEsales\GraphQL\Base\Framework\PermissionProviderInterface;
use OxidSupport\Heartbeat\Component\DiagnosticsProvider\Framework\PermissionProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PermissionProvider::class)]
final class PermissionProviderTest extends TestCase
{
    public function testImplementsPermissionProviderInterface(): void
    {
        $provider = new PermissionProvider();

        $this->assertInstanceOf(PermissionProviderInterface::class, $provider);
    }

    public function testGetPermissionsReturnsArray(): void
    {
        $provider = new PermissionProvider();

        $this->assertIsArray($provider->getPermissions());
    }

    public function testGetPermissionsContainsApiUserGroup(): void
    {
        $provider = new PermissionProvider();
        $permissions = $provider->getPermissions();

        $this->assertArrayHasKey('oxsheartbeat_api', $permissions);
    }

    public function testGetPermissionsContainsAdminGroup(): void
    {
        $provider = new PermissionProvider();
        $permissions = $provider->getPermissions();

        $this->assertArrayHasKey('oxidadmin', $permissions);
    }

    public function testApiUserGroupHasLogSenderViewPermission(): void
    {
        $provider = new PermissionProvider();
        $permissions = $provider->getPermissions();

        $this->assertContains('LOG_SENDER_VIEW', $permissions['oxsheartbeat_api']);
    }

    public function testAdminGroupHasLogSenderViewPermission(): void
    {
        $provider = new PermissionProvider();
        $permissions = $provider->getPermissions();

        $this->assertContains('LOG_SENDER_VIEW', $permissions['oxidadmin']);
    }

    public function testApiUserGroupHasExactlyOnePermission(): void
    {
        $provider = new PermissionProvider();
        $permissions = $provider->getPermissions();

        $this->assertCount(1, $permissions['oxsheartbeat_api']);
    }

    public function testAdminGroupHasExactlyOnePermission(): void
    {
        $provider = new PermissionProvider();
        $permissions = $provider->getPermissions();

        $this->assertCount(1, $permissions['oxidadmin']);
    }

    public function testBothGroupsHaveSamePermissions(): void
    {
        $provider = new PermissionProvider();
        $permissions = $provider->getPermissions();

        $this->assertEquals(
            $permissions['oxsheartbeat_api'],
            $permissions['oxidadmin']
        );
    }

    public function testGetPermissionsReturnsOnlyTwoGroups(): void
    {
        $provider = new PermissionProvider();

        $this->assertCount(2, $provider->getPermissions());
    }

    public function testClassIsFinal(): void
    {
        $reflection = new \ReflectionClass(PermissionProvider::class);

        $this->assertTrue($reflection->isFinal());
    }
}
