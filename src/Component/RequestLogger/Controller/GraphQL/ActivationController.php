<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\RequestLogger\Controller\GraphQL;

use OxidSupport\Heartbeat\Component\RequestLogger\Service\Remote\ActivationServiceInterface;
use OxidSupport\Heartbeat\Component\RequestLogger\Service\Remote\RemoteComponentStatusServiceInterface;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Annotations\Right;

final class ActivationController
{
    public function __construct(
        private ActivationServiceInterface $activationService,
        private RemoteComponentStatusServiceInterface $componentStatusService
    ) {
    }

    #[Query]
    #[Logged]
    #[Right('REQUEST_LOGGER_VIEW')]
    public function requestLoggerIsActive(): bool
    {
        $this->componentStatusService->assertComponentActive();
        return $this->activationService->isActive();
    }

    #[Mutation]
    #[Logged]
    #[Right('REQUEST_LOGGER_ACTIVATE')]
    public function requestLoggerActivate(): bool
    {
        $this->componentStatusService->assertComponentActive();
        return $this->activationService->activate();
    }

    #[Mutation]
    #[Logged]
    #[Right('REQUEST_LOGGER_ACTIVATE')]
    public function requestLoggerDeactivate(): bool
    {
        $this->componentStatusService->assertComponentActive();
        return $this->activationService->deactivate();
    }
}
