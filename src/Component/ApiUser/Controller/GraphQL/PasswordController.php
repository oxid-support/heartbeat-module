<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\ApiUser\Controller\GraphQL;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidSupport\LoggingFramework\Module\Module;
use OxidSupport\LoggingFramework\Component\ApiUser\Exception\InvalidTokenException;
use OxidSupport\LoggingFramework\Component\ApiUser\Exception\PasswordTooShortException;
use OxidSupport\LoggingFramework\Component\ApiUser\Exception\SetupNotAvailableException;
use OxidSupport\LoggingFramework\Component\ApiUser\Service\ApiUserServiceInterface;
use OxidSupport\LoggingFramework\Component\ApiUser\Service\TokenGeneratorInterface;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Right;

final class PasswordController
{
    public function __construct(
        private ApiUserServiceInterface $apiUserService,
        private ModuleSettingServiceInterface $moduleSettingService,
        private TokenGeneratorInterface $tokenGenerator
    ) {
    }

    /**
     * Set the password for the Logging Framework API user.
     * Requires a valid setup token. Token is invalidated after use.
     */
    #[Mutation]
    public function loggingFrameworkSetPassword(string $token, string $password): bool
    {
        $this->assertSetupAvailable();
        $this->validateToken($token);
        $this->validatePassword($password);

        // Security: Clear token BEFORE setting password to prevent race conditions (TOCTOU)
        // This ensures a second concurrent request with the same token will fail validation
        $this->moduleSettingService->saveString(Module::SETTING_APIUSER_SETUP_TOKEN, '', Module::ID);

        // Delegate to service
        $this->apiUserService->setPasswordForApiUser($password);

        return true;
    }

    /**
     * Reset the password for the Logging Framework API user to a placeholder value.
     * This generates a new setup token that can be used with loggingFrameworkSetPassword.
     * Requires admin authentication.
     */
    #[Mutation]
    #[Logged]
    #[Right('OXSLOGGINGFRAMEWORK_PASSWORD_RESET')]
    public function loggingFrameworkResetPassword(): string
    {
        // Generate new setup token
        $token = $this->tokenGenerator->generate();

        // Delegate to service
        $this->apiUserService->resetPasswordForApiUser();

        // Save token
        $this->moduleSettingService->saveString(Module::SETTING_APIUSER_SETUP_TOKEN, $token, Module::ID);

        return $token;
    }

    /**
     * Assert that setup is available (token exists).
     */
    private function assertSetupAvailable(): void
    {
        try {
            $storedToken = (string) $this->moduleSettingService->getString(
                Module::SETTING_APIUSER_SETUP_TOKEN,
                Module::ID
            );
        } catch (\Throwable) {
            throw new SetupNotAvailableException();
        }

        if (empty($storedToken)) {
            throw new SetupNotAvailableException();
        }
    }

    private function validateToken(string $token): void
    {
        try {
            $storedToken = (string) $this->moduleSettingService->getString(
                Module::SETTING_APIUSER_SETUP_TOKEN,
                Module::ID
            );
        } catch (\Throwable) {
            throw new InvalidTokenException();
        }

        // Security: Use constant-time comparison to prevent timing attacks
        if (empty($storedToken) || !hash_equals($storedToken, $token)) {
            throw new InvalidTokenException();
        }
    }

    private function validatePassword(string $password): void
    {
        if (strlen($password) < 8) {
            throw new PasswordTooShortException();
        }
    }
}
