<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\LogSender\Exception;

use Exception;

/**
 * Exception thrown when a log path does not exist.
 */
final class LogPathNotFoundException extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct(
            sprintf('Log path not found: %s', $path)
        );
    }
}
