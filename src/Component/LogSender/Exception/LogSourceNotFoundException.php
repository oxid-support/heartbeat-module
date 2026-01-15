<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\LogSender\Exception;

use Exception;

/**
 * Exception thrown when a log source does not exist.
 */
final class LogSourceNotFoundException extends Exception
{
    public function __construct(string $sourceId)
    {
        parent::__construct(
            sprintf('Log source not found: %s', $sourceId)
        );
    }
}
