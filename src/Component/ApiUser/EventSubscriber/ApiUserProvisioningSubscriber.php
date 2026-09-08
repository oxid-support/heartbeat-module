<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\ApiUser\EventSubscriber;

use OxidSupport\Heartbeat\Component\ApiUser\Event\ApiUserProvisioningRequestedEvent;
use OxidSupport\Heartbeat\Component\ApiUser\Service\ApiUserProvisioningServiceInterface;
use OxidSupport\Heartbeat\Component\ApiUser\Service\SetupTokenServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ApiUserProvisioningSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ApiUserProvisioningServiceInterface $apiUserProvisioningService,
        private readonly SetupTokenServiceInterface $setupTokenService
    ) {
    }

    /**
     * Creates the api group, the service user and the group membership for the current
     * shop, then reconciles the setup token: a fresh per-shop token while the service
     * user has no password, cleared once it has one. Idempotent, it runs on every
     * activation and once per EE subshop, and it replaces the former data-seeding
     * migration. See OXS-3046 and OXS-3103.
     *
     * The event carries no payload, the services read the shop context themselves, so the
     * handler takes no argument; the dispatcher passing one is harmless.
     */
    public function provision(): void
    {
        $this->apiUserProvisioningService->ensureApiUser();
        $this->setupTokenService->ensureSetupToken();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ApiUserProvisioningRequestedEvent::NAME => 'provision',
        ];
    }
}
