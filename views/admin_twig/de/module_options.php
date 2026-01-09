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

    // Logging Framework Navigation
    'mxloggingframework' => 'OXS :: Logging Framework',
    'mxloggingframework_apiuser' => 'API User',
    'tbclloggingframework_apiuser_setup' => 'Einrichtung',
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

    // ==========================================================================
    // API User Component
    // ==========================================================================
    'OXSLOGGINGFRAMEWORK_APIUSER_TITLE' => 'API User',
    'OXSLOGGINGFRAMEWORK_APIUSER_DESC' => 'Verwaltet den API-Benutzer für den Fernzugriff auf das Logging Framework.',
    'OXSLOGGINGFRAMEWORK_APIUSER_STATUS_READY' => 'Bereit',
    'OXSLOGGINGFRAMEWORK_APIUSER_STATUS_SETUP_REQUIRED' => 'Einrichtung erforderlich',
    'OXSLOGGINGFRAMEWORK_APIUSER_INFO_TITLE' => 'Wichtig',
    'OXSLOGGINGFRAMEWORK_APIUSER_INFO_TEXT' => 'Der API User ist erforderlich für alle Komponenten, die Fernzugriff benötigen (z.B. Request Logger Remote). Richten Sie diesen zuerst ein.',

    // API User Setup Workflow
    'OXSLOGGINGFRAMEWORK_APIUSER_SETUP_TITLE' => 'Einrichtungs-Workflow',
    'OXSLOGGINGFRAMEWORK_APIUSER_STEP_INSTALL' => 'Modul installiert',
    'OXSLOGGINGFRAMEWORK_APIUSER_STEP_MIGRATE' => 'Migrationen ausgeführt',
    'OXSLOGGINGFRAMEWORK_APIUSER_MIGRATION_REQUIRED_TEXT' => 'Die Datenbank-Migrationen wurden noch nicht ausgeführt. Bitte führen Sie folgenden Befehl aus:',
    'OXSLOGGINGFRAMEWORK_APIUSER_STEP_GRAPHQL_BASE' => 'GraphQL Base Modul aktiviert',
    'OXSLOGGINGFRAMEWORK_APIUSER_STEP_GRAPHQL_BASE_DESC' => 'Aktivieren mit: ./vendor/bin/oe-console oe:module:activate oe_graphql_base',
    'OXSLOGGINGFRAMEWORK_APIUSER_STEP_ACTIVATE' => 'Logging Framework Modul aktiviert',
    'OXSLOGGINGFRAMEWORK_APIUSER_STEP_ACTIVATE_WARNING' => 'Modul wurde aktiviert ohne vorher die Migrationen auszuführen. Bitte deaktivieren, Migrationen ausführen und erneut aktivieren.',
    'OXSLOGGINGFRAMEWORK_APIUSER_STEP_SEND_TOKEN' => 'Setup-Token an OXID Support senden',
    'OXSLOGGINGFRAMEWORK_APIUSER_STEP_SEND_TOKEN_DESC' => 'Kopieren Sie den Token unten und senden Sie ihn per E-Mail an support@oxid-esales.com',
    'OXSLOGGINGFRAMEWORK_APIUSER_STEP_WAIT_SUPPORT' => 'Warten auf OXID Support zur Aktivierung des API-Zugangs',
    'OXSLOGGINGFRAMEWORK_APIUSER_PREREQUISITES_WARNING' => 'Wichtig: Ohne das GraphQL Base Modul kann der Support den Token nicht verwenden!',
    'OXSLOGGINGFRAMEWORK_APIUSER_COPIED' => 'Kopiert!',
    'OXSLOGGINGFRAMEWORK_APIUSER_SETUP_COMPLETE_TITLE' => 'API User eingerichtet',
    'OXSLOGGINGFRAMEWORK_APIUSER_SETUP_COMPLETE_TEXT' => 'Der API User wurde erfolgreich konfiguriert. Komponenten wie Request Logger Remote können nun aktiviert werden.',

    // API User Reset
    'OXSLOGGINGFRAMEWORK_APIUSER_RESET_TITLE' => 'API-Zugang zurücksetzen',
    'OXSLOGGINGFRAMEWORK_APIUSER_RESET_DESCRIPTION' => 'Diese Aktion setzt das Passwort des API-Benutzers zurück und generiert einen neuen Setup-Token. Verwenden Sie dies nur wenn der Fernzugriff neu eingerichten werden muss.',
    'OXSLOGGINGFRAMEWORK_APIUSER_WARNING_1' => 'Das aktuelle API-Passwort wird ungültig',
    'OXSLOGGINGFRAMEWORK_APIUSER_WARNING_2' => 'Alle bestehenden Remote-Sitzungen werden sofort beendet',
    'OXSLOGGINGFRAMEWORK_APIUSER_WARNING_3' => 'OXID Support verliert den Zugriff bis ein neuer Token zur Verfügung gestellt und ein neues Passwort gesetzt wird',
    'OXSLOGGINGFRAMEWORK_APIUSER_WARNING_4' => 'Sie müssen den neuen Token an OXID Support senden, um den Zugriff wiederherzustellen',
    'OXSLOGGINGFRAMEWORK_APIUSER_CONFIRM_RESET' => 'Ich verstehe die Konsequenzen und möchte das Passwort zurücksetzen',
    'OXSLOGGINGFRAMEWORK_APIUSER_CONFIRM_DIALOG' => 'Sind Sie absolut sicher? Dies widerruft sofort allen Fernzugriff!',
    'OXSLOGGINGFRAMEWORK_APIUSER_RESET_BUTTON' => 'Passwort zurücksetzen & neuen Token generieren',

    // ==========================================================================
    // Request Logger Remote Component (simplified - API User setup moved out)
    // ==========================================================================
    'OXSLOGGINGFRAMEWORK_REMOTE_STATUS_BLOCKED' => 'Gesperrt',
    'OXSLOGGINGFRAMEWORK_REMOTE_BLOCKED_TITLE' => 'API User erforderlich',
    'OXSLOGGINGFRAMEWORK_REMOTE_BLOCKED_TEXT' => 'Diese Komponente benötigt einen eingerichteten API User. Bitte richten Sie zuerst den API User ein.',
    'OXSLOGGINGFRAMEWORK_REMOTE_GOTO_APIUSER' => 'Zur API User Einrichtung',
    'OXSLOGGINGFRAMEWORK_REMOTE_CONFIG_ACCESS_REQUIRED_TITLE' => 'GraphQL Configuration Access erforderlich',
    'OXSLOGGINGFRAMEWORK_REMOTE_CONFIG_ACCESS_REQUIRED_TEXT' => 'Diese Komponente benötigt das GraphQL Configuration Access Modul. Bitte aktivieren Sie es:',
    'OXSLOGGINGFRAMEWORK_REMOTE_READY_TITLE' => 'Remote-Zugriff aktiviert',
    'OXSLOGGINGFRAMEWORK_REMOTE_READY_TEXT' => 'Der OXID Support kann nun auf die Request Logger Einstellungen zugreifen.',
];
