<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\RequestLogger\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;
use OxidSupport\LoggingFramework\Module\Module;
use OxidSupport\LoggingFramework\Shared\Controller\Admin\AbstractComponentController;
use OxidSupport\LoggingFramework\Shared\Controller\Admin\TogglableComponentInterface;

/**
 * Request Logger settings controller for the Logging Framework.
 * Allows configuration of the Request Logger component.
 */
class SettingsController extends AbstractComponentController implements TogglableComponentInterface
{
    protected $_sThisTemplate = '@oxsloggingframework/admin/loggingframework_requestlogger_settings';

    public function isComponentActive(): bool
    {
        return $this->getModuleSettingService()->getBoolean(
            Module::SETTING_REQUESTLOGGER_ACTIVE,
            Module::ID
        );
    }

    public function toggleComponent(): void
    {
        if (!$this->canToggle()) {
            return;
        }

        $this->getModuleSettingService()->saveBoolean(
            Module::SETTING_REQUESTLOGGER_ACTIVE,
            !$this->isComponentActive(),
            Module::ID
        );
    }

    public function canToggle(): bool
    {
        // Request Logger has no prerequisites
        return true;
    }

    /**
     * Get current settings for the template.
     */
    public function getSettings(): array
    {
        $moduleSettingService = $this->getModuleSettingService();
        $moduleId = Module::ID;

        return [
            'componentActive' => $moduleSettingService->getBoolean(Module::SETTING_REQUESTLOGGER_ACTIVE, $moduleId),
            'logLevel' => (string) $moduleSettingService->getString(Module::SETTING_REQUESTLOGGER_LOG_LEVEL, $moduleId),
            'logFrontend' => $moduleSettingService->getBoolean(Module::SETTING_REQUESTLOGGER_LOG_FRONTEND, $moduleId),
            'logAdmin' => $moduleSettingService->getBoolean(Module::SETTING_REQUESTLOGGER_LOG_ADMIN, $moduleId),
            'redactAllValues' => $moduleSettingService->getBoolean(Module::SETTING_REQUESTLOGGER_REDACT_ALL_VALUES, $moduleId),
            'redactFields' => $moduleSettingService->getCollection(Module::SETTING_REQUESTLOGGER_REDACT_FIELDS, $moduleId),
        ];
    }

    /**
     * Save settings from form submission.
     */
    public function save(): void
    {
        $params = Registry::getRequest()->getRequestParameter('editval');
        if (!is_array($params)) {
            return;
        }

        $moduleSettingService = $this->getModuleSettingService();
        $moduleId = Module::ID;

        if (isset($params['logLevel'])) {
            $moduleSettingService->saveString(
                Module::SETTING_REQUESTLOGGER_LOG_LEVEL,
                $params['logLevel'],
                $moduleId
            );
        }

        $moduleSettingService->saveBoolean(
            Module::SETTING_REQUESTLOGGER_LOG_FRONTEND,
            isset($params['logFrontend']) && $params['logFrontend'] === '1',
            $moduleId
        );

        $moduleSettingService->saveBoolean(
            Module::SETTING_REQUESTLOGGER_LOG_ADMIN,
            isset($params['logAdmin']) && $params['logAdmin'] === '1',
            $moduleId
        );

        $moduleSettingService->saveBoolean(
            Module::SETTING_REQUESTLOGGER_REDACT_ALL_VALUES,
            isset($params['redactAllValues']) && $params['redactAllValues'] === '1',
            $moduleId
        );

        if (isset($params['redactFields'])) {
            $fields = array_filter(
                array_map('trim', explode("\n", $params['redactFields']))
            );
            $moduleSettingService->saveCollection(
                Module::SETTING_REQUESTLOGGER_REDACT_FIELDS,
                $fields,
                $moduleId
            );
        }
    }
}
