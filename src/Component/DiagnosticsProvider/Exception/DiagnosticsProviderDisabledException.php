<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\DiagnosticsProvider\Exception;

use Exception;

final class DiagnosticsProviderDisabledException extends Exception
{
    public function __construct()
    {
        parent::__construct('Diagnostics Provider component is disabled.');
    }
}
