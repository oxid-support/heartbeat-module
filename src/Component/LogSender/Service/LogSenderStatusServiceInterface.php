<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\LogSender\Service;

use OxidSupport\LoggingFramework\Component\LogSender\Exception\LogSenderDisabledException;

/**
 * Service for checking the Log Sender component status.
 */
interface LogSenderStatusServiceInterface
{
    /**
     * Check if the Log Sender component is active.
     */
    public function isActive(): bool;

    /**
     * Get the maximum bytes allowed per read request.
     */
    public function getMaxBytes(): int;

    /**
     * Assert that the Log Sender component is active.
     *
     * @throws LogSenderDisabledException
     */
    public function assertComponentActive(): void;
}
