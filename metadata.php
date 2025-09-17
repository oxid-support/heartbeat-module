<?php
declare(strict_types=1);

use OxidEsales\Eshop\Core\ShopControl;
use OxidSupport\RequestLogger\Module\Module as LoggingFrameworkModule;


$sMetadataVersion = '2.1';

$aModule = [
    'id' => LoggingFrameworkModule::ID,
    'title' => 'Minimal Invasive Massive Logging',
    'description' => 'This module provides detailed request logging for OXID eShop, capturing what users do inside the shop.
It records key request data such as visited pages, parameters, and context, making user flows and issues traceable.',
    'version' => '1.0.0',
    'author' => 'support@oxid-esales.com',
    'extend' => [
        ShopControl::class => \OxidSupport\RequestLogger\Shop\Extend\Core\ShopControl::class,
    ]
];
