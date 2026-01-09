<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Shared\Controller\Admin;

/**
 * Interface for component controllers that support on/off toggling.
 *
 * Extends ComponentControllerInterface with toggle-specific functionality.
 * Used by components like Request Logger and Request Logger Remote.
 */
interface TogglableComponentInterface extends ComponentControllerInterface
{
    /**
     * Toggle the component activation state.
     *
     * Called from the admin template when the user clicks the toggle switch.
     * Implementations should check prerequisites before allowing activation.
     */
    public function toggleComponent(): void;

    /**
     * Check if the toggle switch can be used.
     *
     * Returns false if prerequisites are not met (e.g., API User not set up).
     * The toggle should be visually disabled in this case.
     */
    public function canToggle(): bool;
}
