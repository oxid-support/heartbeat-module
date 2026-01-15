<?php

declare(strict_types=1);

// Load stub interfaces for unit testing (only if the real interface is not available)
if (!interface_exists(\OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface::class)) {
    require_once __DIR__ . '/Unit/Stub/ModuleSettingServiceInterface.php';
}

// Load the main autoloader
require_once __DIR__ . '/../../../../vendor/autoload.php';
