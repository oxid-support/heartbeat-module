<?php
declare(strict_types=1);

use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\ShopControl;
use OxidSupport\Logger\Bootstrap\Module as LoggingFrameworkModule;
use OxidSupport\Logger\Shop\Core\FrontendController as FrontendControllerExtend;
use OxidSupport\Logger\Shop\Core\ShopControl as ShopControlExtend;

$sMetadataVersion = '2.1';

$aModule = [
    'id' => LoggingFrameworkModule::ID,
    'title' => 'Minimal Invasive Massive Logging',
    'description' => 'PSR-3 Logging mit Request-Kontext, Error/Exception/Shutdown Hooks',
    'version' => '1.0.0',
    'author' => 'support@oxid-esales.com',
    'extend' => [
        ShopControl::class => ShopControlExtend::class,
        FrontendController::class => FrontendControllerExtend::class,
    ],
    'events' => [
        'onActivate'    => 'OxidSupport\\Logger\\Bootstrap\\HandlerRegistrar::onActivate',
        'onDeactivate'  => 'OxidSupport\\Logger\\Bootstrap\\HandlerRegistrar::onDeactivate',
    ],
];
