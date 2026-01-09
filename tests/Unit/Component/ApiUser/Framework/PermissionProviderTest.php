<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Tests\Unit\Component\ApiUser\Framework;

use OxidEsales\GraphQL\Base\Framework\PermissionProviderInterface;
use OxidSupport\LoggingFramework\Component\ApiUser\Framework\PermissionProvider;
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
        $permissions = $provider->getPermissions();

        $this->assertIsArray($permissions);
    }

    public function testGetPermissionsContainsApiUserGroup(): void
    {
        $provider = new PermissionProvider();
        $permissions = $provider->getPermissions();

        $this->assertArrayHasKey('oxsloggingframework_api', $permissions);
    }

    public function testGetPermissionsContainsAdminGroup(): void
    {
        $provider = new PermissionProvider();
        $permissions = $provider->getPermissions();

        $this->assertArrayHasKey('oxidadmin', $permissions);
    }

    public function testApiUserGroupHasPasswordResetPermission(): void
    {
        $provider = new PermissionProvider();
        $permissions = $provider->getPermissions();

        $this->assertContains('OXSLOGGINGFRAMEWORK_PASSWORD_RESET', $permissions['oxsloggingframework_api']);
    }

    public function testAdminGroupHasPasswordResetPermission(): void
    {
        $provider = new PermissionProvider();
        $permissions = $provider->getPermissions();

        $this->assertContains('OXSLOGGINGFRAMEWORK_PASSWORD_RESET', $permissions['oxidadmin']);
    }

    public function testApiUserGroupHasExactlyOnePermission(): void
    {
        $provider = new PermissionProvider();
        $permissions = $provider->getPermissions();

        $this->assertCount(1, $permissions['oxsloggingframework_api']);
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

        $apiPermissions = $permissions['oxsloggingframework_api'];
        $adminPermissions = $permissions['oxidadmin'];

        sort($apiPermissions);
        sort($adminPermissions);

        $this->assertSame($apiPermissions, $adminPermissions);
    }

    public function testGetPermissionsReturnsOnlyTwoGroups(): void
    {
        $provider = new PermissionProvider();
        $permissions = $provider->getPermissions();

        $this->assertCount(2, $permissions);
    }
}
