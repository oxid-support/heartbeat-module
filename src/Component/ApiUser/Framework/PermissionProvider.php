<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\ApiUser\Framework;

use OxidEsales\GraphQL\Base\Framework\PermissionProviderInterface;

final class PermissionProvider implements PermissionProviderInterface
{
    public function getPermissions(): array
    {
        return [
            // Custom user group for Logging Framework API access
            'oxsloggingframework_api' => [
                'OXSLOGGINGFRAMEWORK_PASSWORD_RESET',
            ],
            // Also grant permissions to shop admins
            'oxidadmin' => [
                'OXSLOGGINGFRAMEWORK_PASSWORD_RESET',
            ],
        ];
    }
}
