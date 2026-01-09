<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\ApiUser\Exception;

use OxidEsales\GraphQL\Base\Exception\Error;

final class SetupNotAvailableException extends Error
{
    public function __construct()
    {
        parent::__construct('API user setup is not available. No setup token exists.');
    }

    public function getCategory(): string
    {
        return 'permission';
    }
}
