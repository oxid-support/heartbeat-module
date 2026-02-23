<?php

declare(strict_types=1);

// Load the module's autoloader (includes oxideshop-ce for test interfaces)
require_once __DIR__ . '/../vendor/autoload.php';

// Register a class alias for the ModuleSettingBridgeInterface.
// OXID 6.5 (oxideshop-ce dev-b-6.5.x) ships the interface under the
// "Configuration\Bridge" namespace, but OXID 7.x moved it to "Setting\Bridge".
// The production source code targets the 7.x namespace because this module
// is installed into shops that provide the newer namespace at runtime.
// For unit tests (where only the 6.5 dev package is installed) we bridge
// the gap with an alias so PHPUnit can mock the interface.
$ns = 'OxidEsales\EshopCommunity\Internal\Framework\Module\\';
$newInterface = $ns . 'Setting\Bridge\ModuleSettingBridgeInterface';
$oldInterface = $ns . 'Configuration\Bridge\ModuleSettingBridgeInterface';

if (!interface_exists($newInterface, false) && interface_exists($oldInterface)) {
    class_alias($oldInterface, $newInterface);
}
