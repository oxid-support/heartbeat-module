<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\LogSender\Framework;

use OxidEsales\GraphQL\Base\Framework\PermissionProviderInterface;

final class PermissionProvider implements PermissionProviderInterface
{
    public function getPermissions(): array
    {
        return [
            // Custom user group for Log Sender API access
            'oxsloggingframework_api' => [
                'LOG_SENDER_VIEW',
            ],
            // Also grant permissions to shop admins
            'oxidadmin' => [
                'LOG_SENDER_VIEW',
            ],
        ];
    }
}
