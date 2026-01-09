<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Shared\Controller\Admin;

/**
 * Interface for Logging Framework component controllers.
 *
 * Provides a consistent API for component status and activation,
 * ensuring all component pages display status information consistently.
 */
interface ComponentControllerInterface
{
    /**
     * Status class constants for consistent status styling.
     */
    public const STATUS_CLASS_ACTIVE = 'active';
    public const STATUS_CLASS_INACTIVE = 'inactive';
    public const STATUS_CLASS_WARNING = 'warning';

    /**
     * Check if the component is currently active/enabled.
     */
    public function isComponentActive(): bool;

    /**
     * Get the CSS class for the status badge.
     *
     * @return string One of: 'active', 'inactive', 'warning'
     */
    public function getStatusClass(): string;

    /**
     * Get the translation key for the status text.
     */
    public function getStatusTextKey(): string;
}
