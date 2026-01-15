<?php
declare(strict_types=1);

use OxidEsales\Eshop\Core\ShopControl;
use OxidSupport\LoggingFramework\Module\Module as LoggingFrameworkModule;

$sMetadataVersion = '2.1';

$aModule = [
    'id' => LoggingFrameworkModule::ID,
    'title' => 'OXS :: Logging Framework',
    'description' => 'This module provides a comprehensive logging framework for OXID eShop.
It includes detailed request logging, capturing what users do inside the shop.
Records key request data such as visited pages, parameters, and context, making user flows and issues traceable.
Includes GraphQL API for remote configuration and activation.',
    'version' => '1.0.0',
    'author' => 'OXID Support',
    'email' => 'support@oxid-esales.com',
    'url' => 'https://oxid-esales.com',
    'extend' => [
        ShopControl::class => \OxidSupport\LoggingFramework\Shop\Extend\Core\ShopControl::class,
        \OxidEsales\Eshop\Application\Controller\Admin\ModuleConfiguration::class =>
            \OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Controller\Admin\ModuleConfigController::class,
        \OxidEsales\Eshop\Application\Controller\Admin\NavigationController::class =>
            \OxidSupport\LoggingFramework\Shared\Controller\Admin\NavigationController::class,
    ],
    'controllers' => [
        'loggingframework_apiuser_setup' => \OxidSupport\LoggingFramework\Component\ApiUser\Controller\Admin\SetupController::class,
        'loggingframework_requestlogger_settings' => \OxidSupport\LoggingFramework\Component\RequestLogger\Controller\Admin\SettingsController::class,
        'loggingframework_remote_setup' => \OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Controller\Admin\SetupController::class,
        'loggingframework_logsender_setup' => \OxidSupport\LoggingFramework\Component\LogSender\Controller\Admin\SetupController::class,
    ],
    'events' => [
        'onActivate' => \OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Core\ModuleEvents::class . '::onActivate',
    ],
    'settings' => [
        // Request Logger component settings
        [
            'group' => LoggingFrameworkModule::ID . '_main',
            'name' => LoggingFrameworkModule::ID . '_requestlogger_log_level',
            'type' => 'select',
            'constraints' => 'standard|detailed',
            'value' => 'standard',
        ],
        [
            'group' => LoggingFrameworkModule::ID . '_main',
            'name' => LoggingFrameworkModule::ID . '_requestlogger_log_frontend',
            'type' => 'bool',
            'value' => false,
        ],
        [
            'group' => LoggingFrameworkModule::ID . '_main',
            'name' => LoggingFrameworkModule::ID . '_requestlogger_log_admin',
            'type' => 'bool',
            'value' => false,
        ],
        [
            'group' => LoggingFrameworkModule::ID . '_main',
            'name' => LoggingFrameworkModule::ID . '_requestlogger_redact_fields',
            'type' => 'arr',
            'value' => [
                'pwd',
                'lgn_pwd',
                'lgn_pwd2',
                'newPassword',
            ],
        ],
        [
            'group' => LoggingFrameworkModule::ID . '_main',
            'name' => LoggingFrameworkModule::ID . '_requestlogger_redact_all_values',
            'type' => 'bool',
            'value' => true,
        ],
        [
            'group' => '',
            'name'  => LoggingFrameworkModule::ID . '_requestlogger_active',
            'type'  => 'bool',
            'value' => false,
        ],
        // API User component settings
        [
            'group' => '',
            'name'  => LoggingFrameworkModule::ID . '_apiuser_setup_token',
            'type'  => 'str',
            'value' => '',
        ],
        // Remote component settings
        [
            'group' => '',
            'name'  => LoggingFrameworkModule::ID . '_remote_active',
            'type'  => 'bool',
            'value' => false,
        ],
        // Log Sender component settings
        [
            'group' => '',
            'name'  => LoggingFrameworkModule::ID . '_logsender_active',
            'type'  => 'bool',
            'value' => false,
        ],
        [
            'group' => '',
            'name'  => LoggingFrameworkModule::ID . '_logsender_static_paths',
            'type'  => 'arr',
            'value' => [],
        ],
        [
            'group' => '',
            'name'  => LoggingFrameworkModule::ID . '_logsender_max_bytes',
            'type'  => 'num',
            'value' => 1048576,
        ],
        [
            'group' => '',
            'name'  => LoggingFrameworkModule::ID . '_logsender_enabled_sources',
            'type'  => 'arr',
            'value' => [],
        ],
    ],
];
