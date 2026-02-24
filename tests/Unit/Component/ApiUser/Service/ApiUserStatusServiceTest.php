<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\ApiUser\Service;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Result;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidSupport\Heartbeat\Component\ApiUser\Service\ApiUserStatusService;
use OxidSupport\Heartbeat\Module\Module;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ApiUserStatusService::class)]
final class ApiUserStatusServiceTest extends TestCase
{
    private const MIGRATION_TABLE = 'oxmigrations_oxsheartbeat';
    private const EXPECTED_MIGRATION = '20251223000001';

    // ===========================================
    // isMigrationExecuted() tests
    // ===========================================

    public function testIsMigrationExecutedReturnsTrueWhenMigrationExists(): void
    {
        $result = $this->createMock(Result::class);
        $result->expects($this->once())
            ->method('fetchOne')
            ->willReturn('1');

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->expects($this->once())->method('select')->with('COUNT(*)')->willReturnSelf();
        $queryBuilder->expects($this->once())->method('from')->with(self::MIGRATION_TABLE)->willReturnSelf();
        $queryBuilder->expects($this->once())->method('where')->with('version = :version')->willReturnSelf();
        $queryBuilder->expects($this->once())->method('setParameter')
            ->with('version', self::EXPECTED_MIGRATION)->willReturnSelf();
        $queryBuilder->expects($this->once())->method('execute')->willReturn($result);

        $queryBuilderFactory = $this->createMock(QueryBuilderFactoryInterface::class);
        $queryBuilderFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($queryBuilder);

        $result = $this->getSut(queryBuilderFactory: $queryBuilderFactory)->isMigrationExecuted();

        $this->assertTrue($result);
    }

    public function testIsMigrationExecutedReturnsFalseWhenMigrationDoesNotExist(): void
    {
        $result = $this->createMock(Result::class);
        $result->expects($this->once())
            ->method('fetchOne')
            ->willReturn('0');

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('from')->willReturnSelf();
        $queryBuilder->method('where')->willReturnSelf();
        $queryBuilder->method('setParameter')->willReturnSelf();
        $queryBuilder->method('execute')->willReturn($result);

        $queryBuilderFactory = $this->createMock(QueryBuilderFactoryInterface::class);
        $queryBuilderFactory->method('create')->willReturn($queryBuilder);

        $result = $this->getSut(queryBuilderFactory: $queryBuilderFactory)->isMigrationExecuted();

        $this->assertFalse($result);
    }

    public function testIsMigrationExecutedReturnsFalseOnException(): void
    {
        $queryBuilderFactory = $this->createMock(QueryBuilderFactoryInterface::class);
        $queryBuilderFactory->method('create')
            ->willThrowException(new \Exception('Database error'));

        $result = $this->getSut(queryBuilderFactory: $queryBuilderFactory)->isMigrationExecuted();

        $this->assertFalse($result);
    }

    // ===========================================
    // isApiUserCreated() tests
    // ===========================================

    public function testIsApiUserCreatedReturnsTrueWhenUserExists(): void
    {
        $result = $this->createMock(Result::class);
        $result->expects($this->once())
            ->method('fetchOne')
            ->willReturn('1');

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->expects($this->once())->method('select')->with('COUNT(*)')->willReturnSelf();
        $queryBuilder->expects($this->once())->method('from')->with('oxuser')->willReturnSelf();
        $queryBuilder->expects($this->once())->method('where')->with('OXUSERNAME = :email')->willReturnSelf();
        $queryBuilder->expects($this->once())->method('setParameter')
            ->with('email', Module::API_USER_EMAIL)->willReturnSelf();
        $queryBuilder->expects($this->once())->method('execute')->willReturn($result);

        $queryBuilderFactory = $this->createMock(QueryBuilderFactoryInterface::class);
        $queryBuilderFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($queryBuilder);

        $result = $this->getSut(queryBuilderFactory: $queryBuilderFactory)->isApiUserCreated();

        $this->assertTrue($result);
    }

    public function testIsApiUserCreatedReturnsFalseWhenUserDoesNotExist(): void
    {
        $result = $this->createMock(Result::class);
        $result->expects($this->once())
            ->method('fetchOne')
            ->willReturn('0');

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('from')->willReturnSelf();
        $queryBuilder->method('where')->willReturnSelf();
        $queryBuilder->method('setParameter')->willReturnSelf();
        $queryBuilder->method('execute')->willReturn($result);

        $queryBuilderFactory = $this->createMock(QueryBuilderFactoryInterface::class);
        $queryBuilderFactory->method('create')->willReturn($queryBuilder);

        $result = $this->getSut(queryBuilderFactory: $queryBuilderFactory)->isApiUserCreated();

        $this->assertFalse($result);
    }

    public function testIsApiUserCreatedReturnsFalseOnException(): void
    {
        $queryBuilderFactory = $this->createMock(QueryBuilderFactoryInterface::class);
        $queryBuilderFactory->method('create')
            ->willThrowException(new \Exception('Database error'));

        $result = $this->getSut(queryBuilderFactory: $queryBuilderFactory)->isApiUserCreated();

        $this->assertFalse($result);
    }

    // ===========================================
    // isApiUserPasswordSet() tests
    // ===========================================

    public function testIsApiUserPasswordSetReturnsTrueWhenPasswordIsSet(): void
    {
        $result = $this->createMock(Result::class);
        $result->expects($this->once())
            ->method('fetchAssociative')
            ->willReturn([
                'OXPASSWORD' => '$2y$10$somehash',
            ]);

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->expects($this->once())->method('select')->with('OXPASSWORD')->willReturnSelf();
        $queryBuilder->expects($this->once())->method('from')->with('oxuser')->willReturnSelf();
        $queryBuilder->expects($this->once())->method('where')->with('OXUSERNAME = :email')->willReturnSelf();
        $queryBuilder->expects($this->once())->method('setParameter')
            ->with('email', Module::API_USER_EMAIL)->willReturnSelf();
        $queryBuilder->expects($this->once())->method('execute')->willReturn($result);

        $queryBuilderFactory = $this->createMock(QueryBuilderFactoryInterface::class);
        $queryBuilderFactory->expects($this->once())->method('create')->willReturn($queryBuilder);

        $result = $this->getSut(queryBuilderFactory: $queryBuilderFactory)->isApiUserPasswordSet();

        $this->assertTrue($result);
    }

    public function testIsApiUserPasswordSetReturnsFalseWhenPasswordIsNotBCrypt(): void
    {
        $result = $this->createMock(Result::class);
        $result->expects($this->once())
            ->method('fetchAssociative')
            ->willReturn([
                'OXPASSWORD' => 'placeholder_hash_not_bcrypt',
            ]);

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('from')->willReturnSelf();
        $queryBuilder->method('where')->willReturnSelf();
        $queryBuilder->method('setParameter')->willReturnSelf();
        $queryBuilder->method('execute')->willReturn($result);

        $queryBuilderFactory = $this->createMock(QueryBuilderFactoryInterface::class);
        $queryBuilderFactory->method('create')->willReturn($queryBuilder);

        $result = $this->getSut(queryBuilderFactory: $queryBuilderFactory)->isApiUserPasswordSet();

        $this->assertFalse($result);
    }

    public function testIsApiUserPasswordSetReturnsFalseWhenUserNotFound(): void
    {
        $result = $this->createMock(Result::class);
        $result->expects($this->once())
            ->method('fetchAssociative')
            ->willReturn(false);

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('from')->willReturnSelf();
        $queryBuilder->method('where')->willReturnSelf();
        $queryBuilder->method('setParameter')->willReturnSelf();
        $queryBuilder->method('execute')->willReturn($result);

        $queryBuilderFactory = $this->createMock(QueryBuilderFactoryInterface::class);
        $queryBuilderFactory->method('create')->willReturn($queryBuilder);

        $result = $this->getSut(queryBuilderFactory: $queryBuilderFactory)->isApiUserPasswordSet();

        $this->assertFalse($result);
    }

    public function testIsApiUserPasswordSetReturnsFalseOnException(): void
    {
        $queryBuilderFactory = $this->createMock(QueryBuilderFactoryInterface::class);
        $queryBuilderFactory->method('create')
            ->willThrowException(new \Exception('Database error'));

        $result = $this->getSut(queryBuilderFactory: $queryBuilderFactory)->isApiUserPasswordSet();

        $this->assertFalse($result);
    }

    // ===========================================
    // isSetupComplete() tests
    // ===========================================

    public function testIsSetupCompleteReturnsTrueWhenAllConditionsMet(): void
    {
        // Mock for migration check
        $migrationResult = $this->createMock(Result::class);
        $migrationResult->method('fetchOne')->willReturn('1');

        // Mock for user exists check
        $userExistsResult = $this->createMock(Result::class);
        $userExistsResult->method('fetchOne')->willReturn('1');

        // Mock for password check - must be BCrypt hash
        $passwordResult = $this->createMock(Result::class);
        $passwordResult->method('fetchAssociative')->willReturn([
            'OXPASSWORD' => '$2y$10$somevalidbcrypthash',
        ]);

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('from')->willReturnSelf();
        $queryBuilder->method('where')->willReturnSelf();
        $queryBuilder->method('setParameter')->willReturnSelf();
        $queryBuilder->method('execute')
            ->willReturnOnConsecutiveCalls($migrationResult, $userExistsResult, $passwordResult);

        $queryBuilderFactory = $this->createMock(QueryBuilderFactoryInterface::class);
        $queryBuilderFactory->method('create')->willReturn($queryBuilder);

        $result = $this->getSut(queryBuilderFactory: $queryBuilderFactory)->isSetupComplete();

        $this->assertTrue($result);
    }

    public function testIsSetupCompleteReturnsFalseWhenMigrationNotExecuted(): void
    {
        $migrationResult = $this->createMock(Result::class);
        $migrationResult->method('fetchOne')->willReturn('0');

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('from')->willReturnSelf();
        $queryBuilder->method('where')->willReturnSelf();
        $queryBuilder->method('setParameter')->willReturnSelf();
        $queryBuilder->method('execute')->willReturn($migrationResult);

        $queryBuilderFactory = $this->createMock(QueryBuilderFactoryInterface::class);
        $queryBuilderFactory->method('create')->willReturn($queryBuilder);

        $result = $this->getSut(queryBuilderFactory: $queryBuilderFactory)->isSetupComplete();

        $this->assertFalse($result);
    }

    public function testIsSetupCompleteReturnsFalseWhenUserNotCreated(): void
    {
        // Migration returns true
        $migrationResult = $this->createMock(Result::class);
        $migrationResult->method('fetchOne')->willReturn('1');

        // User exists returns false
        $userExistsResult = $this->createMock(Result::class);
        $userExistsResult->method('fetchOne')->willReturn('0');

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('from')->willReturnSelf();
        $queryBuilder->method('where')->willReturnSelf();
        $queryBuilder->method('setParameter')->willReturnSelf();
        $queryBuilder->method('execute')
            ->willReturnOnConsecutiveCalls($migrationResult, $userExistsResult);

        $queryBuilderFactory = $this->createMock(QueryBuilderFactoryInterface::class);
        $queryBuilderFactory->method('create')->willReturn($queryBuilder);

        $result = $this->getSut(queryBuilderFactory: $queryBuilderFactory)->isSetupComplete();

        $this->assertFalse($result);
    }

    public function testIsSetupCompleteReturnsFalseWhenPasswordNotSet(): void
    {
        // Migration returns true
        $migrationResult = $this->createMock(Result::class);
        $migrationResult->method('fetchOne')->willReturn('1');

        // User exists returns true
        $userExistsResult = $this->createMock(Result::class);
        $userExistsResult->method('fetchOne')->willReturn('1');

        // Password is placeholder (empty salt)
        $passwordResult = $this->createMock(Result::class);
        $passwordResult->method('fetchAssociative')->willReturn([
            'OXPASSWORD' => 'placeholder',
            'OXPASSSALT' => '',
        ]);

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('from')->willReturnSelf();
        $queryBuilder->method('where')->willReturnSelf();
        $queryBuilder->method('setParameter')->willReturnSelf();
        $queryBuilder->method('execute')
            ->willReturnOnConsecutiveCalls($migrationResult, $userExistsResult, $passwordResult);

        $queryBuilderFactory = $this->createMock(QueryBuilderFactoryInterface::class);
        $queryBuilderFactory->method('create')->willReturn($queryBuilder);

        $result = $this->getSut(queryBuilderFactory: $queryBuilderFactory)->isSetupComplete();

        $this->assertFalse($result);
    }

    private function getSut(
        ?QueryBuilderFactoryInterface $queryBuilderFactory = null,
    ): ApiUserStatusService {
        return new ApiUserStatusService(
            queryBuilderFactory: $queryBuilderFactory ?? $this->createStub(QueryBuilderFactoryInterface::class),
        );
    }
}
