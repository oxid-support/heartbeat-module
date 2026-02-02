<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\DiagnosticsProvider\Service;

use OxidEsales\Eshop\Application\Model\Diagnostics;

interface DiagnosticsProviderInterface
{
    public function getDiagnosticsModel(): Diagnostics;

    /**
     * @return array<string, mixed>
     */
    public function getDiagnostics(): array;

    /**
     * @return array<string, \OxidEsales\Eshop\Core\Module\Module>
     */
    public function getModuleList(): array;
}
