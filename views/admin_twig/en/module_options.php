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

    // Migration warning
    'OXSREQUESTLOGGER_MIGRATION_REQUIRED_TEXT' => 'The database migrations have not been executed yet. Please run the following command:',

    // Setup workflow
    'OXSREQUESTLOGGER_SETUP_TITLE' => 'Setup Workflow',
    'OXSREQUESTLOGGER_SETUP_STEP_INSTALL' => 'Module installed',
    'OXSREQUESTLOGGER_SETUP_STEP_MIGRATE' => 'Migrations executed',
    'OXSREQUESTLOGGER_SETUP_STEP_GRAPHQL_BASE' => 'GraphQL Base module activated',
    'OXSREQUESTLOGGER_SETUP_STEP_GRAPHQL_BASE_DESC' => 'Activate with: ./vendor/bin/oe-console oe:module:activate oe_graphql_base',
    'OXSREQUESTLOGGER_SETUP_STEP_CONFIG_ACCESS' => 'GraphQL Configuration Access module activated',
    'OXSREQUESTLOGGER_SETUP_STEP_CONFIG_ACCESS_DESC' => 'Activate with: ./vendor/bin/oe-console oe:module:activate oe_graphql_configuration_access',
    'OXSREQUESTLOGGER_SETUP_STEP_ACTIVATE' => 'Request Logger module activated',
    'OXSREQUESTLOGGER_SETUP_STEP_ACTIVATE_WARNING' => 'Module was activated without executing migrations first. Please deactivate, run migrations, and activate again.',
    'OXSREQUESTLOGGER_SETUP_STEP_SEND_TOKEN' => 'Send setup token to OXID Support',
    'OXSREQUESTLOGGER_SETUP_STEP_SEND_TOKEN_DESC' => 'Copy the token below and send it via email to support@oxid-esales.com',
    'OXSREQUESTLOGGER_SETUP_STEP_WAIT_SUPPORT' => 'Wait for OXID Support to activate API access',
    'OXSREQUESTLOGGER_SETUP_PREREQUISITES_WARNING' => 'Important: Without these modules, support cannot use the token!',
    'OXSREQUESTLOGGER_SETUP_COPIED' => 'Copied!',
    'OXSREQUESTLOGGER_SETUP_COMPLETE_TITLE' => 'Setup Complete',
    'OXSREQUESTLOGGER_SETUP_COMPLETE_TEXT' => 'Remote access has been successfully configured. OXID Support can now access the Request Logger settings.',

    // Password Reset Admin Page
    'OXSREQUESTLOGGER_PASSWORD_RESET_TITLE' => 'API User Password Reset',
    'OXSREQUESTLOGGER_PASSWORD_RESET_MENU' => 'Password Reset',
    'OXSREQUESTLOGGER_API_RESET_TITLE' => 'Reset API Access',
    'OXSREQUESTLOGGER_RESET_DESCRIPTION' => 'This action resets the password of the Request Logger API user and generates a new setup token. Use this only if remote access needs to be set up again.',
    'OXSREQUESTLOGGER_WARNING_1' => 'The current API password will be invalidated',
    'OXSREQUESTLOGGER_WARNING_2' => 'All existing remote sessions will be terminated immediately',
    'OXSREQUESTLOGGER_WARNING_3' => 'OXID Support will lose access until a new token is provided and a new password is set.',
    'OXSREQUESTLOGGER_WARNING_4' => 'You must send the new token to OXID Support to restore access',
    'OXSREQUESTLOGGER_CONFIRM_RESET' => 'I understand the consequences and want to reset the password',
    'OXSREQUESTLOGGER_CONFIRM_DIALOG' => 'Are you absolutely sure? This will immediately revoke all remote access!',
    'OXSREQUESTLOGGER_RESET_BUTTON' => 'Reset Password & Generate New Token',
    'OXSREQUESTLOGGER_RESET_SUCCESS' => 'Password has been reset successfully!',
    'OXSREQUESTLOGGER_NEW_TOKEN_INFO' => 'A new setup token has been generated. Please send this token to OXID Support:',
    'OXSREQUESTLOGGER_TOKEN_COPY_HINT' => 'Copy this token and send it to support@oxid-esales.com',
    'OXSREQUESTLOGGER_RESET_ERROR' => 'An error occurred: ',
    'OXSREQUESTLOGGER_ERROR_USER_NOT_FOUND' => 'The API user could not be found. Please ensure the module migrations have been executed.',
    'OXSREQUESTLOGGER_TOKEN_EXISTS_INFO' => 'A setup token already exists. The password has not been set yet. If you need to generate a new token, use the reset function below.',

    // Logging Framework Navigation
    'mxloggingframework' => 'OXS :: Logging Framework',
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
];
