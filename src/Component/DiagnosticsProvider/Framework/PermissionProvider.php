<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\DiagnosticsProvider\Framework;

use OxidEsales\GraphQL\Base\Framework\PermissionProviderInterface;

final class PermissionProvider implements PermissionProviderInterface
{
    public function getPermissions(): array
    {
        return [
                        // Custom user group for Log Sender API access
            'oxsheartbeat_api' => [
                'LOG_SENDER_VIEW',
            ],
            // Also grant permissions to shop admins
            'oxidadmin' => [
                'LOG_SENDER_VIEW',
            ],
        ];
    }
}
