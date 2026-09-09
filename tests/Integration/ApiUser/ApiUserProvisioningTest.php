<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Integration\ApiUser;

use Doctrine\DBAL\Connection;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Base\Tests\Integration\TokenTestCase;
use OxidSupport\Heartbeat\Component\ApiUser\Event\ApiUserProvisioningRequestedEvent;
use OxidSupport\Heartbeat\Module\Module;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Integration test for the api user provisioning that replaced the former
 * data-seeding migration. See OXS-3046.
 *
 * The regression this guards: activation creates the service user with the
 * CURRENT shop id (via the model layer and the shop's id generator), never a
 * hardcoded OXSHOPID = 1, and it is idempotent. The EE multi-subshop case (one
 * service user across several subshops) is out of scope here and tracked in
 * OXS-3103.
 *
 * Non-destructive: TokenTestCase wraps each test in a DB transaction that is
 * rolled back in tearDown.
 */
final class ApiUserProvisioningTest extends TokenTestCase
{
    public function testEnsureApiUserCreatesUserGroupAndMembershipForCurrentShop(): void
    {
        $shopId = (int) Registry::getConfig()->getShopId();

        // Start from a clean slate so we exercise the creation path, not the
        // already-provisioned no-op (the module is activated in the test env).
        $this->removeApiUserAndMembership();
        $this->assertSame(0, $this->apiUserCount(), 'precondition: no api user');

        $this->provisionThroughTheModuleWiring();

        $userId = $this->apiUserId();
        $this->assertNotEmpty($userId, 'api user created');
        $this->assertSame(1, $this->groupCount(), 'api group created');
        $this->assertSame(1, $this->membershipCount($userId), 'membership created');
        // The meaningful fix: the user row carries the activating shop id, not a
        // hardcoded 1 (this is what EE login filters on). See OXS-3046 / OXS-3103.
        $this->assertSame(
            $shopId,
            $this->apiUserShopId(),
            'service user stamped with the current shop id, not a hardcoded 1'
        );
    }

    public function testEnsureApiUserIsIdempotent(): void
    {
        $this->removeApiUserAndMembership();

        $this->provisionThroughTheModuleWiring();
        $this->provisionThroughTheModuleWiring();

        $userId = $this->apiUserId();
        $this->assertSame(1, $this->apiUserCount(), 'no duplicate user');
        $this->assertSame(1, $this->membershipCount($userId), 'no duplicate membership');
    }

    /**
     * Provisions the way the module does it: the activation hook only dispatches the
     * event, ApiUserProvisioningSubscriber holds the services. The services themselves
     * are private, so the test takes the same route the shop takes instead of asking the
     * container for something the shop never fetches either. See OXS-3377.
     */
    private function provisionThroughTheModuleWiring(): void
    {
        $dispatcher = ContainerFactory::getInstance()
            ->getContainer()
            ->get(EventDispatcherInterface::class);

        $this->assertTrue(
            $dispatcher->hasListeners(ApiUserProvisioningRequestedEvent::NAME),
            'the module subscribes to its own provisioning event'
        );

        $dispatcher->dispatch(
            new ApiUserProvisioningRequestedEvent(),
            ApiUserProvisioningRequestedEvent::NAME
        );
    }

    private function connection(): Connection
    {
        return ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class)
            ->create()
            ->getConnection();
    }

    private function removeApiUserAndMembership(): void
    {
        $userId = $this->apiUserId();
        if ($userId !== '') {
            $this->connection()->executeStatement(
                'DELETE FROM oxobject2group WHERE OXOBJECTID = :uid',
                ['uid' => $userId]
            );
            $this->connection()->executeStatement(
                'DELETE FROM oxuser WHERE OXID = :uid',
                ['uid' => $userId]
            );
        }
    }

    private function apiUserId(): string
    {
        return (string) $this->connection()->executeQuery(
            'SELECT OXID FROM oxuser WHERE OXUSERNAME = :email',
            ['email' => Module::API_USER_EMAIL]
        )->fetchOne();
    }

    private function apiUserCount(): int
    {
        return (int) $this->connection()->executeQuery(
            'SELECT COUNT(*) FROM oxuser WHERE OXUSERNAME = :email',
            ['email' => Module::API_USER_EMAIL]
        )->fetchOne();
    }

    private function groupCount(): int
    {
        return (int) $this->connection()->executeQuery(
            "SELECT COUNT(*) FROM oxgroups WHERE OXID = 'oxsheartbeat_api'"
        )->fetchOne();
    }

    private function membershipCount(string $userId): int
    {
        return (int) $this->connection()->executeQuery(
            "SELECT COUNT(*) FROM oxobject2group
             WHERE OXOBJECTID = :uid AND OXGROUPSID = 'oxsheartbeat_api'",
            ['uid' => $userId]
        )->fetchOne();
    }

    private function apiUserShopId(): int
    {
        return (int) $this->connection()->executeQuery(
            'SELECT OXSHOPID FROM oxuser WHERE OXUSERNAME = :email',
            ['email' => Module::API_USER_EMAIL]
        )->fetchOne();
    }
}
