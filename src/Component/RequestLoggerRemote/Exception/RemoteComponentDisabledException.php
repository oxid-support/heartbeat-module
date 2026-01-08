<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Exception;

use OxidEsales\GraphQL\Base\Exception\Error;

final class RemoteComponentDisabledException extends Error
{
    public function __construct()
    {
        parent::__construct('Remote component is disabled. Enable it in the admin panel to use the GraphQL API.');
    }

    public function getCategory(): string
    {
        return 'permission';
    }
}
