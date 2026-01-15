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
    'mxloggingframework_logsender' => 'Log Sender',
    'tbclloggingframework_logsender_setup' => 'Einrichtung',

    // Logging Framework Component Status
    'OXSREQUESTLOGGER_LF_STATUS_ACTIVE' => 'Aktiv',
    'OXSREQUESTLOGGER_LF_STATUS_INACTIVE' => 'Inaktiv',
    'OXSREQUESTLOGGER_LF_COMPONENT_ACTIVATION' => 'Komponente aktivieren',
    'OXSREQUESTLOGGER_LF_COMPONENT_ACTIVATION_DESC' => 'Schalten Sie diese Komponente ein oder aus.',

    // Logging Framework Request Logger
    'OXSREQUESTLOGGER_LF_REQUESTLOGGER_TITLE' => 'Request Logger',
    'OXSREQUESTLOGGER_LF_REQUESTLOGGER_DESC' => 'Konfiguriert die Protokollierung von Shop-Requests zur Fehleranalyse.',

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
    'OXSLOGGINGFRAMEWORK_APIUSER_STATUS_READY' => 'Aktiv',
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
    'OXSLOGGINGFRAMEWORK_REMOTE_STATUS_WARNING' => 'Einrichtung erforderlich',
    'OXSLOGGINGFRAMEWORK_REMOTE_WARNING_TITLE' => 'API User erforderlich',
    'OXSLOGGINGFRAMEWORK_REMOTE_WARNING_TEXT' => 'Diese Komponente benötigt einen eingerichteten API User. Bitte richten Sie zuerst den API User ein.',
    'OXSLOGGINGFRAMEWORK_REMOTE_GOTO_APIUSER' => 'Zur API User Einrichtung',
    'OXSLOGGINGFRAMEWORK_REMOTE_CONFIG_ACCESS_REQUIRED_TITLE' => 'GraphQL Configuration Access erforderlich',
    'OXSLOGGINGFRAMEWORK_REMOTE_CONFIG_ACCESS_REQUIRED_TEXT' => 'Diese Komponente benötigt das GraphQL Configuration Access Modul. Bitte aktivieren Sie es:',
    'OXSLOGGINGFRAMEWORK_REMOTE_READY_TITLE' => 'Remote-Zugriff aktiviert',
    'OXSLOGGINGFRAMEWORK_REMOTE_READY_TEXT' => 'Der OXID Support kann nun auf die Request Logger Einstellungen zugreifen.',

    // ==========================================================================
    // Log Sender Component
    // ==========================================================================
    'OXSLOGGINGFRAMEWORK_LOGSENDER_TITLE' => 'Log Sender',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_DESC' => 'Sammelt Log-Dateien und stellt sie dem Heartbeat Monitor zur Verfügung.',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_STATUS_WARNING' => 'Einrichtung erforderlich',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_WARNING_TITLE' => 'API User erforderlich',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_WARNING_TEXT' => 'Diese Komponente benötigt einen eingerichteten API User. Bitte richten Sie zuerst den API User ein.',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_GOTO_APIUSER' => 'Zur API User Einrichtung',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_READY_TITLE' => 'Log Sender aktiviert',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_READY_TEXT' => 'Der Heartbeat Monitor kann nun auf die Log-Quellen zugreifen.',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_SOURCES_TITLE' => 'Erkannte Log-Quellen',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_NO_SOURCES' => 'Keine Log-Quellen konfiguriert. Registrieren Sie Provider über DI-Tags oder konfigurieren Sie statische Pfade.',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_HOWTO_TITLE' => 'Log-Quellen hinzufügen',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_HOWTO_TEXT' => 'Es gibt zwei Wege, Log-Quellen zu registrieren:',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_HOWTO_PROVIDER' => 'DI Tag Provider',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_HOWTO_PROVIDER_DESC' => 'Services implementieren LogPathProviderInterface und werden mit dem Tag "oxs.logsender.provider" registriert.',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_HOWTO_STATIC' => 'Statische Pfade',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_HOWTO_STATIC_DESC' => 'Pfade werden direkt in den Modul-Settings konfiguriert (für Drittanbieter-Logs).',

    // Log Sender - Static Paths Configuration
    'OXSLOGGINGFRAMEWORK_LOGSENDER_STATIC_TITLE' => 'Statische Log-Pfade',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_STATIC_DESC' => 'Konfigurieren Sie hier zusätzliche Log-Dateien oder -Verzeichnisse, die überwacht werden sollen.',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_STATIC_PATHS_LABEL' => 'Log-Pfade (ein Pfad pro Zeile)',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_STATIC_PATHS_PLACEHOLDER' => '/var/log/myapp.log
/var/log/custom/',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_STATIC_PATHS_HELP' => 'Geben Sie absolute Pfade an. Pfade mit "/" am Ende werden als Verzeichnis behandelt, alle anderen als Datei.',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_SAVE' => 'Speichern',

    // Log Sender - Path Validation
    'OXSLOGGINGFRAMEWORK_LOGSENDER_VALIDATION_TITLE' => 'Pfad-Validierung',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_TYPE_FILE' => 'Datei',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_TYPE_DIRECTORY' => 'Verzeichnis',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_ERROR_NOT_FOUND' => 'Pfad existiert nicht',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_ERROR_NOT_READABLE' => 'Pfad nicht lesbar (fehlende Berechtigungen)',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_ERROR_TYPE_MISMATCH' => 'Typ-Konflikt',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_ERROR_CANNOT_LIST' => 'Verzeichnis kann nicht aufgelistet werden',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_EXPECTED' => 'Erwartet',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_FOUND' => 'Gefunden',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_FILES_FOUND' => 'Dateien gefunden',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_SIZE' => 'Größe',
    'OXSLOGGINGFRAMEWORK_LOGSENDER_TOGGLE_SOURCE' => 'Log-Quelle zum Senden aktivieren/deaktivieren',
];
