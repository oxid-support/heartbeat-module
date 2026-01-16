<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\DiagnosticsProvider\Service;

use OxidEsales\Eshop\Application\Model\Diagnostics;

interface DiagnosticsProviderInterface {

    public function getDiagnosticsModel(): Diagnostics;
}