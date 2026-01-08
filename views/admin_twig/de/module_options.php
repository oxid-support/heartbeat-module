<?php

declare(strict_types=1);

use OxidSupport\LoggingFramework\Module\Module as RequestLoggerModule;

$aLang = [
    'charset' => 'UTF-8',
    'SHOP_MODULE_GROUP_' . RequestLoggerModule::ID . '_main' => 'Einstellungen',
    'SHOP_MODULE_' . RequestLoggerModule::ID . '_log-level' => 'Log Level',
    'SHOP_MODULE_' . RequestLoggerModule::ID . '_log-level_standard' => 'Standard',
    'SHOP_MODULE_' . RequestLoggerModule::ID . '_log-level_detailed' => 'Detailliert',
    'SHOP_MODULE_' . RequestLoggerModule::ID . '_redact-all-values' => 'Alle Werte zensieren',
    'SHOP_MODULE_' . RequestLoggerModule::ID . '_redact' => 'Zensieren',
    'SHOP_MODULE_' . RequestLoggerModule::ID . '_log-frontend' => 'Frontend-Anfragen protokollieren',
    'SHOP_MODULE_' . RequestLoggerModule::ID . '_log-admin' => 'Admin-Anfragen protokollieren',

    // Migration warning
    'OXSREQUESTLOGGER_MIGRATION_REQUIRED_TEXT' => 'Die Datenbank-Migrationen wurden noch nicht ausgeführt. Bitte führen Sie folgenden Befehl aus:',

    // Setup workflow
    'OXSREQUESTLOGGER_SETUP_TITLE' => 'Einrichtungs-Workflow',
    'OXSREQUESTLOGGER_SETUP_STEP_INSTALL' => 'Modul installiert',
    'OXSREQUESTLOGGER_SETUP_STEP_MIGRATE' => 'Migrationen ausgeführt',
    'OXSREQUESTLOGGER_SETUP_STEP_GRAPHQL_BASE' => 'GraphQL Base Modul aktiviert',
    'OXSREQUESTLOGGER_SETUP_STEP_GRAPHQL_BASE_DESC' => 'Aktivieren mit: ./vendor/bin/oe-console oe:module:activate oe_graphql_base',
    'OXSREQUESTLOGGER_SETUP_STEP_CONFIG_ACCESS' => 'GraphQL Configuration Access Modul aktiviert',
    'OXSREQUESTLOGGER_SETUP_STEP_CONFIG_ACCESS_DESC' => 'Aktivieren mit: ./vendor/bin/oe-console oe:module:activate oe_graphql_configuration_access',
    'OXSREQUESTLOGGER_SETUP_STEP_ACTIVATE' => 'Request Logger Modul aktiviert',
    'OXSREQUESTLOGGER_SETUP_STEP_ACTIVATE_WARNING' => 'Modul wurde aktiviert ohne vorher die Migrationen auszuführen. Bitte deaktivieren, Migrationen ausführen und erneut aktivieren.',
    'OXSREQUESTLOGGER_SETUP_STEP_SEND_TOKEN' => 'Setup-Token an OXID Support senden',
    'OXSREQUESTLOGGER_SETUP_STEP_SEND_TOKEN_DESC' => 'Kopieren Sie den Token unten und senden Sie ihn per E-Mail an support@oxid-esales.com',
    'OXSREQUESTLOGGER_SETUP_STEP_WAIT_SUPPORT' => 'Warten auf OXID Support zur Aktivierung des API-Zugangs',
    'OXSREQUESTLOGGER_SETUP_PREREQUISITES_WARNING' => 'Wichtig: Ohne diese Module kann der Support den Token nicht verwenden!',
    'OXSREQUESTLOGGER_SETUP_COPIED' => 'Kopiert!',
    'OXSREQUESTLOGGER_SETUP_COMPLETE_TITLE' => 'Einrichtung abgeschlossen',
    'OXSREQUESTLOGGER_SETUP_COMPLETE_TEXT' => 'Der Fernzugriff wurde erfolgreich konfiguriert. Der OXID Support kann nun auf die Request Logger Einstellungen zugreifen.',

    // Password Reset Admin Page
    'OXSREQUESTLOGGER_PASSWORD_RESET_TITLE' => 'API-Benutzer Passwort zurücksetzen',
    'OXSREQUESTLOGGER_PASSWORD_RESET_MENU' => 'Passwort zurücksetzen',
    'OXSREQUESTLOGGER_API_RESET_TITLE' => 'API-Zugang zurücksetzen',
    'OXSREQUESTLOGGER_RESET_DESCRIPTION' => 'Diese Aktion setzt das Passwort des Request Logger API-Benutzers zurück und generiert einen neuen Setup-Token. Verwenden Sie dies nur wenn der Fernzugriff neu eingerichten werden muss.',
    'OXSREQUESTLOGGER_WARNING_1' => 'Das aktuelle API-Passwort wird ungültig',
    'OXSREQUESTLOGGER_WARNING_2' => 'Alle bestehenden Remote-Sitzungen werden sofort beendet',
    'OXSREQUESTLOGGER_WARNING_3' => 'OXID Support verliert den Zugriff bis ein neuer Token zur Verfügung gestellt und ein neues Passwort gesetzt wird',
    'OXSREQUESTLOGGER_WARNING_4' => 'Sie müssen den neuen Token an OXID Support senden, um den Zugriff wiederherzustellen',
    'OXSREQUESTLOGGER_CONFIRM_RESET' => 'Ich verstehe die Konsequenzen und möchte das Passwort zurücksetzen',
    'OXSREQUESTLOGGER_CONFIRM_DIALOG' => 'Sind Sie absolut sicher? Dies widerruft sofort allen Fernzugriff!',
    'OXSREQUESTLOGGER_RESET_BUTTON' => 'Passwort zurücksetzen & neuen Token generieren',
    'OXSREQUESTLOGGER_RESET_SUCCESS' => 'Passwort wurde erfolgreich zurückgesetzt!',
    'OXSREQUESTLOGGER_NEW_TOKEN_INFO' => 'Ein neuer Setup-Token wurde generiert. Bitte senden Sie diesen Token an OXID Support:',
    'OXSREQUESTLOGGER_TOKEN_COPY_HINT' => 'Kopieren Sie diesen Token und senden Sie ihn an support@oxid-esales.com',
    'OXSREQUESTLOGGER_RESET_ERROR' => 'Ein Fehler ist aufgetreten: ',
    'OXSREQUESTLOGGER_ERROR_USER_NOT_FOUND' => 'Der API-Benutzer wurde nicht gefunden. Bitte stellen Sie sicher, dass die Modul-Migrationen ausgeführt wurden.',
    'OXSREQUESTLOGGER_TOKEN_EXISTS_INFO' => 'Ein Setup-Token existiert bereits. Das Passwort wurde noch nicht gesetzt. Wenn Sie einen neuen Token generieren müssen, verwenden Sie die Zurücksetzen-Funktion unten.',

    // Logging Framework Navigation
    'mxloggingframework' => 'OXS :: Logging Framework',
    'mxloggingframework_requestlogger' => 'Request Logger',
    'tbclloggingframework_requestlogger_settings' => 'Einstellungen',
    'mxloggingframework_remote' => 'Request Logger Remote',
    'tbclloggingframework_remote_setup' => 'Einrichtung',

    // Logging Framework Component Status
    'OXSREQUESTLOGGER_LF_STATUS_ACTIVE' => 'Aktiv',
    'OXSREQUESTLOGGER_LF_STATUS_INACTIVE' => 'Inaktiv',
    'OXSREQUESTLOGGER_LF_COMPONENT_ACTIVATION' => 'Komponente aktivieren',
    'OXSREQUESTLOGGER_LF_COMPONENT_ACTIVATION_DESC' => 'Schalten Sie diese Komponente ein oder aus.',

    // Logging Framework Request Logger
    'OXSREQUESTLOGGER_LF_REQUESTLOGGER_TITLE' => 'Request Logger',
    'OXSREQUESTLOGGER_LF_REQUESTLOGGER_DESC' => 'Protokolliert Benutzeraktionen und Requests im Shop zur Fehleranalyse.',

    // Logging Framework Request Logger Settings
    'OXSREQUESTLOGGER_LF_SETTINGS_ACTIVATION' => 'Aktivierung',
    'OXSREQUESTLOGGER_LF_SETTINGS_LOGGING' => 'Protokollierung',
    'OXSREQUESTLOGGER_LF_SETTINGS_REDACTION' => 'Anonymisierung',
    'OXSREQUESTLOGGER_LF_SETTINGS_LOG_FRONTEND' => 'Frontend protokollieren',
    'OXSREQUESTLOGGER_LF_SETTINGS_LOG_FRONTEND_HELP' => 'Aktiviert die Protokollierung von Frontend-Anfragen.',
    'OXSREQUESTLOGGER_LF_SETTINGS_LOG_ADMIN' => 'Admin protokollieren',
    'OXSREQUESTLOGGER_LF_SETTINGS_LOG_ADMIN_HELP' => 'Aktiviert die Protokollierung von Admin-Anfragen.',
    'OXSREQUESTLOGGER_LF_SETTINGS_DETAILED_LOGGING' => 'Detailliertes Logging',
    'OXSREQUESTLOGGER_LF_SETTINGS_DETAILED_LOGGING_HELP' => 'Aktiviert erweiterte Protokollierung mit mehr Details.',
    'OXSREQUESTLOGGER_LF_SETTINGS_REDACT_ALL' => 'Alle Werte anonymisieren',
    'OXSREQUESTLOGGER_LF_SETTINGS_REDACT_ALL_HELP' => 'Zensiert alle Parameterwerte im Log.',
    'OXSREQUESTLOGGER_LF_SETTINGS_REDACT_FIELDS' => 'Felder anonymisieren',
    'OXSREQUESTLOGGER_LF_SETTINGS_REDACT_FIELDS_HELP' => 'Liste der Feldnamen (einer pro Zeile), deren Werte zensiert werden sollen.',
    'OXSREQUESTLOGGER_LF_SETTINGS_SAVE' => 'Speichern',

    // Logging Framework Remote
    'OXSREQUESTLOGGER_LF_REMOTE_TITLE' => 'Request Logger Remote',
    'OXSREQUESTLOGGER_LF_REMOTE_DESC' => 'Ermöglicht dem OXID Support, den Request Logger aus der Ferne zu konfigurieren.',
];
