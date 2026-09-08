<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\RequestLogger\Core;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerBuilderFactory;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidSupport\Heartbeat\Component\ApiUser\Service\ApiUserProvisioningServiceInterface;
use OxidSupport\Heartbeat\Component\ApiUser\Service\SetupTokenServiceInterface;
use OxidSupport\Heartbeat\Component\ApiUser\Service\TokenInvalidatorInterface;
use OxidSupport\Heartbeat\Module\Module;
use Psr\Container\ContainerInterface;

final class ModuleEvents
{
    /**
     * Called on module activation. Seeds the api user, its group and the group
     * membership for the current shop, then reconciles the setup token for that
     * shop (delegated to SetupTokenService). See OXS-3046 / OXS-3103.
     */
    public static function onActivate(): void
    {
        // Module activation intentionally does NOT run database migrations
        // (schema is an operator/pipeline concern, see OXS-3066). The module
        // ships no migrations anymore; the api user, its group and the group
        // membership are seeded below in the current shop context. See OXS-3046.
        // For the same reason the views are deliberately not regenerated here:
        // the module changes no schema, so there is nothing for DbMetaDataHandler
        // to pick up, and the call cost every activation a view rebuild per shop.
        // See OXS-3375.
        // The caches are not cleared here either: the core invalidates the module
        // caches on this very event (InvalidateModuleCacheEventSubscriber on
        // FinalizingModuleActivationEvent, FilesystemModuleCache::invalidate), which
        // covers the shop's template cache, the language, menu and module-variable
        // caches and the module path cache, more than the module ever did.
        // See OXS-3376.

        try {
            $container = self::buildContainerWithModuleServices();

            // Create the api group, the service user and the group membership for
            // the current shop. Idempotent, runs on every activation, and replaces
            // the former data-seeding migration. This is the single creation path;
            // there is no migration to run first. See OXS-3046.
            $container->get(ApiUserProvisioningServiceInterface::class)->ensureApiUser();

            // Reconcile the setup token with this shop's service-user password:
            // a fresh per-shop token while the password is unset, cleared once it is
            // set. Shop-scoped, so EE subshops never share or retain the base shop's
            // inherited token (the only gate on the unauthenticated
            // heartbeatSetPassword mutation). See OXS-3103.
            $container->get(SetupTokenServiceInterface::class)->ensureSetupToken();
        } catch (\Throwable $e) {
            self::reportProvisioningFailure($e);
        }
    }

    /**
     * Called on module deactivation.
     * Invalidates all JWTs of the heartbeat-api service user so that no stale
     * token can keep the dormant module accessible from outside. See OXS-3054.
     */
    public static function onDeactivate(): void
    {
        try {
            $container = ContainerFactory::getInstance()->getContainer();
            $tokenInvalidator = $container->get(TokenInvalidatorInterface::class);
            $tokenInvalidator->invalidateForApiUser();
        } catch (\Throwable $e) {
            // Module is being deactivated. Swallow lookup failures (e.g. service
            // not registered, api user missing) because we must not block the
            // deactivation flow itself. The tokens become useless without the
            // module routes anyway.
        }
    }

    /**
     * Compiles a fresh container from the current generated_services.yaml instead of
     * asking ContainerFactory for one.
     *
     * The activating request booted with the container that was cached while this
     * module was still inactive, so that container has none of the module's services.
     * The core resets ContainerFactory once the module's services.yaml is registered,
     * but the reset only deletes the cache file: FilesystemContainerCache::get() loads
     * the file with include_once, so when a concurrent request rewrites it before this
     * hook runs, this process gets a new instance of the stale ProjectServiceContainer
     * class declared at boot, and "You have requested a non-existent service" follows.
     * ContainerFactory::resetContainer() right before getContainer() only narrows that
     * window. Compiling here reads the yaml directly and never touches the cache file
     * or that class. This is also what the shop's own graphql-base module does in
     * ModuleSetup::onActivate(), the module event api has no dependency injection.
     */
    /**
     * Keeps a failed provisioning inside the module instead of aborting the activation.
     *
     * The container above carries the service definitions of every active module, so a
     * defect in a foreign module surfaces here too, and the shop runs into the same error
     * on its next cold compile anyway. Letting that stacktrace end the activation would
     * blame this module and leave the operator without a next step, so the activation
     * finishes: the API User page then shows "setup required", and activating again
     * repeats the provisioning, which is idempotent. See OXS-3377.
     */
    private static function reportProvisioningFailure(\Throwable $e): void
    {
        Registry::getLogger()->error(
            'Heartbeat: api user provisioning failed during module activation: ' . $e->getMessage()
            . ' Fix the cause, then activate the module again.',
            [$e]
        );

        // addErrorToDisplay() stores the message in the session and starts one if needed,
        // which makes no sense in a console activation; there the log is the only channel.
        if (PHP_SAPI !== 'cli') {
            Registry::getUtilsView()->addErrorToDisplay(Module::TRANSLATION_PROVISIONING_FAILED);
        }
    }

    private static function buildContainerWithModuleServices(): ContainerInterface
    {
        $container = (new ContainerBuilderFactory())->create()->getContainer();
        $container->compile();

        return $container;
    }
}
