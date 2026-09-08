<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\ApiUser\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Asks for the api user, its group, the membership and the setup token of the current
 * shop. Dispatched by the activation hook, handled by ApiUserProvisioningSubscriber, so
 * the provisioning itself runs as an ordinary service with injected dependencies instead
 * of being pulled out of a container by hand. See OXS-3377.
 */
final class ApiUserProvisioningRequestedEvent extends Event
{
    public const NAME = self::class;
}
