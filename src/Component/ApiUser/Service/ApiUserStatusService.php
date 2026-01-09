<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\ApiUser\Service;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidSupport\LoggingFramework\Module\Module;

/**
 * Service to check the setup status of the API user.
 * Checks actual database state rather than assuming based on module activation.
 */
final class ApiUserStatusService implements ApiUserStatusServiceInterface
{
    private const MIGRATION_TABLE = 'oxmigrations_oxsloggingframework';
    private const EXPECTED_MIGRATION = 'OxidSupport\\LoggingFramework\\Migrations\\Version20251223000001';

    public function __construct(
        private readonly QueryBuilderFactoryInterface $queryBuilderFactory
    ) {
    }

    public function isMigrationExecuted(): bool
    {
        try {
            $queryBuilder = $this->queryBuilderFactory->create();
            $result = $queryBuilder
                ->select('COUNT(*)')
                ->from(self::MIGRATION_TABLE)
                ->where('version = :version')
                ->setParameter('version', self::EXPECTED_MIGRATION)
                ->execute();

            return (int) $result->fetchOne() > 0;
        } catch (\Exception) {
            // Table doesn't exist or other error - migration not executed
            return false;
        }
    }

    public function isApiUserCreated(): bool
    {
        try {
            $queryBuilder = $this->queryBuilderFactory->create();
            $result = $queryBuilder
                ->select('COUNT(*)')
                ->from('oxuser')
                ->where('OXUSERNAME = :email')
                ->setParameter('email', Module::API_USER_EMAIL)
                ->execute();

            return (int) $result->fetchOne() > 0;
        } catch (\Exception) {
            return false;
        }
    }

    public function isApiUserPasswordSet(): bool
    {
        try {
            $queryBuilder = $this->queryBuilderFactory->create();
            $result = $queryBuilder
                ->select('OXPASSWORD')
                ->from('oxuser')
                ->where('OXUSERNAME = :email')
                ->setParameter('email', Module::API_USER_EMAIL)
                ->execute();

            $row = $result->fetchAssociative();

            if (!$row) {
                return false;
            }

            $password = $row['OXPASSWORD'] ?? '';

            // Password is set if it's a valid bcrypt hash (starts with $2y$)
            // The placeholder '---' or empty string means password not set
            return !empty($password) && str_starts_with($password, '$2y$');
        } catch (\Exception) {
            return false;
        }
    }

    public function isSetupComplete(): bool
    {
        return $this->isMigrationExecuted()
            && $this->isApiUserCreated()
            && $this->isApiUserPasswordSet();
    }
}
