<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\RequestLogger\Framework;

use OxidEsales\GraphQL\Base\Framework\PermissionProviderInterface;

final class PermissionProvider implements PermissionProviderInterface
{
    public function getPermissions(): array
    {
        return [
            'oxsheartbeat_api' => [
                'REQUEST_LOGGER_VIEW',
                'REQUEST_LOGGER_CHANGE',
            ],
            'oxidadmin' => [
                'REQUEST_LOGGER_VIEW',
                'REQUEST_LOGGER_CHANGE',
            ],
        ];
    }
}
