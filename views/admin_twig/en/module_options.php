<?php

declare(strict_types=1);

use OxidSupport\LoggingFramework\Module\Module as RequestLoggerModule;

$aLang = [
    'charset' => 'UTF-8',
    'SHOP_MODULE_GROUP_' . RequestLoggerModule::ID . '_main' => 'Settings',
    'SHOP_MODULE_' . RequestLoggerModule::ID . '_log-level' => 'Log Level',
    'SHOP_MODULE_' . RequestLoggerModule::ID . '_log-level_standard' => 'Standard',
    'SHOP_MODULE_' . RequestLoggerModule::ID . '_log-level_detailed' => 'Detailed',
    'SHOP_MODULE_' . RequestLoggerModule::ID . '_redact-all-values' => 'Redact all values',
    'SHOP_MODULE_' . RequestLoggerModule::ID . '_redact' => 'Redact',
    'SHOP_MODULE_' . RequestLoggerModule::ID . '_log-frontend' => 'Log Frontend Requests',
    'SHOP_MODULE_' . RequestLoggerModule::ID . '_log-admin' => 'Log Admin Requests',

    // Logging Framework Navigation
    'mxloggingframework' => 'OXS :: Logging Framework',
    'mxloggingframework_apiuser' => 'API User',
    'tbclloggingframework_apiuser_setup' => 'Setup',
    'mxloggingframework_requestlogger' => 'Request Logger',
    'tbclloggingframework_requestlogger_settings' => 'Settings',
    'mxloggingframework_remote' => 'Request Logger Remote',
    'tbclloggingframework_remote_setup' => 'Setup',

    // Logging Framework Component Status
    'OXSREQUESTLOGGER_LF_STATUS_ACTIVE' => 'Active',
    'OXSREQUESTLOGGER_LF_STATUS_INACTIVE' => 'Inactive',
    'OXSREQUESTLOGGER_LF_COMPONENT_ACTIVATION' => 'Activate Component',
    'OXSREQUESTLOGGER_LF_COMPONENT_ACTIVATION_DESC' => 'Toggle this component on or off.',

    // Logging Framework Request Logger
    'OXSREQUESTLOGGER_LF_REQUESTLOGGER_TITLE' => 'Request Logger',
    'OXSREQUESTLOGGER_LF_REQUESTLOGGER_DESC' => 'Logs user actions and requests in the shop for error analysis.',

    // Logging Framework Request Logger Settings
    'OXSREQUESTLOGGER_LF_SETTINGS_ACTIVATION' => 'Activation',
    'OXSREQUESTLOGGER_LF_SETTINGS_LOGGING' => 'Logging',
    'OXSREQUESTLOGGER_LF_SETTINGS_REDACTION' => 'Redaction',
    'OXSREQUESTLOGGER_LF_SETTINGS_LOG_FRONTEND' => 'Log Frontend',
    'OXSREQUESTLOGGER_LF_SETTINGS_LOG_FRONTEND_HELP' => 'Enables logging of frontend requests.',
    'OXSREQUESTLOGGER_LF_SETTINGS_LOG_ADMIN' => 'Log Admin',
    'OXSREQUESTLOGGER_LF_SETTINGS_LOG_ADMIN_HELP' => 'Enables logging of admin requests.',
    'OXSREQUESTLOGGER_LF_SETTINGS_DETAILED_LOGGING' => 'Detailed Logging',
    'OXSREQUESTLOGGER_LF_SETTINGS_DETAILED_LOGGING_HELP' => 'Enables extended logging with more details.',
    'OXSREQUESTLOGGER_LF_SETTINGS_REDACT_ALL' => 'Redact All Values',
    'OXSREQUESTLOGGER_LF_SETTINGS_REDACT_ALL_HELP' => 'Redacts all parameter values in the log.',
    'OXSREQUESTLOGGER_LF_SETTINGS_REDACT_FIELDS' => 'Redact Fields',
    'OXSREQUESTLOGGER_LF_SETTINGS_REDACT_FIELDS_HELP' => 'List of field names (one per line) whose values should be redacted.',
    'OXSREQUESTLOGGER_LF_SETTINGS_SAVE' => 'Save',

    // Logging Framework Remote
    'OXSREQUESTLOGGER_LF_REMOTE_TITLE' => 'Request Logger Remote',
    'OXSREQUESTLOGGER_LF_REMOTE_DESC' => 'Allows OXID Support to configure the Request Logger remotely.',

    // ==========================================================================
    // API User Component
    // ==========================================================================
    'OXSLOGGINGFRAMEWORK_APIUSER_TITLE' => 'API User',
    'OXSLOGGINGFRAMEWORK_APIUSER_DESC' => 'Manages the API user for remote access to the Logging Framework.',
    'OXSLOGGINGFRAMEWORK_APIUSER_STATUS_READY' => 'Ready',
    'OXSLOGGINGFRAMEWORK_APIUSER_STATUS_SETUP_REQUIRED' => 'Setup Required',
    'OXSLOGGINGFRAMEWORK_APIUSER_INFO_TITLE' => 'Important',
    'OXSLOGGINGFRAMEWORK_APIUSER_INFO_TEXT' => 'The API User is required for all components that need remote access (e.g., Request Logger Remote). Set this up first.',

    // API User Setup Workflow
    'OXSLOGGINGFRAMEWORK_APIUSER_SETUP_TITLE' => 'Setup Workflow',
    'OXSLOGGINGFRAMEWORK_APIUSER_STEP_INSTALL' => 'Module installed',
    'OXSLOGGINGFRAMEWORK_APIUSER_STEP_MIGRATE' => 'Migrations executed',
    'OXSLOGGINGFRAMEWORK_APIUSER_MIGRATION_REQUIRED_TEXT' => 'The database migrations have not been executed yet. Please run the following command:',
    'OXSLOGGINGFRAMEWORK_APIUSER_STEP_GRAPHQL_BASE' => 'GraphQL Base module activated',
    'OXSLOGGINGFRAMEWORK_APIUSER_STEP_GRAPHQL_BASE_DESC' => 'Activate with: ./vendor/bin/oe-console oe:module:activate oe_graphql_base',
    'OXSLOGGINGFRAMEWORK_APIUSER_STEP_ACTIVATE' => 'Logging Framework module activated',
    'OXSLOGGINGFRAMEWORK_APIUSER_STEP_ACTIVATE_WARNING' => 'Module was activated without executing migrations first. Please deactivate, run migrations, and activate again.',
    'OXSLOGGINGFRAMEWORK_APIUSER_STEP_SEND_TOKEN' => 'Send setup token to OXID Support',
    'OXSLOGGINGFRAMEWORK_APIUSER_STEP_SEND_TOKEN_DESC' => 'Copy the token below and send it via email to support@oxid-esales.com',
    'OXSLOGGINGFRAMEWORK_APIUSER_STEP_WAIT_SUPPORT' => 'Wait for OXID Support to activate API access',
    'OXSLOGGINGFRAMEWORK_APIUSER_PREREQUISITES_WARNING' => 'Important: Without the GraphQL Base module, support cannot use the token!',
    'OXSLOGGINGFRAMEWORK_APIUSER_COPIED' => 'Copied!',
    'OXSLOGGINGFRAMEWORK_APIUSER_SETUP_COMPLETE_TITLE' => 'API User Set Up',
    'OXSLOGGINGFRAMEWORK_APIUSER_SETUP_COMPLETE_TEXT' => 'The API User has been successfully configured. Components like Request Logger Remote can now be activated.',

    // API User Reset
    'OXSLOGGINGFRAMEWORK_APIUSER_RESET_TITLE' => 'Reset API Access',
    'OXSLOGGINGFRAMEWORK_APIUSER_RESET_DESCRIPTION' => 'This action resets the password of the API user and generates a new setup token. Use this only if remote access needs to be set up again.',
    'OXSLOGGINGFRAMEWORK_APIUSER_WARNING_1' => 'The current API password will be invalidated',
    'OXSLOGGINGFRAMEWORK_APIUSER_WARNING_2' => 'All existing remote sessions will be terminated immediately',
    'OXSLOGGINGFRAMEWORK_APIUSER_WARNING_3' => 'OXID Support will lose access until a new token is provided and a new password is set',
    'OXSLOGGINGFRAMEWORK_APIUSER_WARNING_4' => 'You must send the new token to OXID Support to restore access',
    'OXSLOGGINGFRAMEWORK_APIUSER_CONFIRM_RESET' => 'I understand the consequences and want to reset the password',
    'OXSLOGGINGFRAMEWORK_APIUSER_CONFIRM_DIALOG' => 'Are you absolutely sure? This will immediately revoke all remote access!',
    'OXSLOGGINGFRAMEWORK_APIUSER_RESET_BUTTON' => 'Reset Password & Generate New Token',

    // ==========================================================================
    // Request Logger Remote Component (simplified - API User setup moved out)
    // ==========================================================================
    'OXSLOGGINGFRAMEWORK_REMOTE_STATUS_BLOCKED' => 'Blocked',
    'OXSLOGGINGFRAMEWORK_REMOTE_BLOCKED_TITLE' => 'API User Required',
    'OXSLOGGINGFRAMEWORK_REMOTE_BLOCKED_TEXT' => 'This component requires a configured API User. Please set up the API User first.',
    'OXSLOGGINGFRAMEWORK_REMOTE_GOTO_APIUSER' => 'Go to API User Setup',
    'OXSLOGGINGFRAMEWORK_REMOTE_CONFIG_ACCESS_REQUIRED_TITLE' => 'GraphQL Configuration Access Required',
    'OXSLOGGINGFRAMEWORK_REMOTE_CONFIG_ACCESS_REQUIRED_TEXT' => 'This component requires the GraphQL Configuration Access module. Please activate it:',
    'OXSLOGGINGFRAMEWORK_REMOTE_READY_TITLE' => 'Remote Access Activated',
    'OXSLOGGINGFRAMEWORK_REMOTE_READY_TEXT' => 'OXID Support can now access the Request Logger settings.',
];
