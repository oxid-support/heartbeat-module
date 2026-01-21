<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\DiagnosticsProvider\Service;

use OxidSupport\Heartbeat\Component\DiagnosticsProvider\Exception\DiagnosticsProviderDisabledException;

/**
 * Service for checking the Diagnostics Provider component status.
 */
interface DiagnosticsProviderStatusServiceInterface
{
    /**
     * Check if the Diagnostics Provider component is active.
     */
    public function isActive(): bool;

    /**
     * Assert that the Diagnostics Provider component is active.
     *
     * @throws DiagnosticsProviderDisabledException
     */
    public function assertComponentActive(): void;
}
