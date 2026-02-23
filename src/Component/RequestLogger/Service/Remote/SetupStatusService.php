<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\RequestLogger\Service\Remote;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

final class SetupStatusService implements SetupStatusServiceInterface
{
    private const MIGRATION_TABLE = 'oxmigrations_oxsheartbeat';
    private const EXPECTED_MIGRATION = 'OxidSupport\\Heartbeat\\Migrations\\Version20251223000001';

    public function __construct(
        private QueryBuilderFactoryInterface $queryBuilderFactory
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

            return (int) $result->fetchOne() > 0; // @phpstan-ignore method.nonObject
        } catch (\Exception) {
            return false;
        }
    }
}
