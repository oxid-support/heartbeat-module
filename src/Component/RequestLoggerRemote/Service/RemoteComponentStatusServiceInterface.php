<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Service;

interface RemoteComponentStatusServiceInterface
{
    /**
     * Check if the remote component is active.
     */
    public function isActive(): bool;

    /**
     * Throws an exception if the remote component is disabled.
     *
     * @throws \OxidSupport\LoggingFramework\Remote\Exception\RemoteComponentDisabledException
     */
    public function assertComponentActive(): void;
}
