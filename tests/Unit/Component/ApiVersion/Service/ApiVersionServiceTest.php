<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\ApiVersion\Service;

use OxidSupport\Heartbeat\Component\ApiVersion\Service\ApiVersionService;
use OxidSupport\Heartbeat\Module\Module;
use PHPUnit\Framework\TestCase;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Query;

final class ApiVersionServiceTest extends TestCase
{
    /** @var string[] Operations not provided by this module (e.g. from graphql-base) */
    private const EXTERNAL_OPERATIONS = [
        'token',
    ];

    public function testGetApiVersionReturnsExpectedFields(): void
    {
        $service = new ApiVersionService();
        $result = $service->getApiVersion();

        $this->assertSame(Module::API_VERSION, $result->getApiVersion());
        $this->assertSame(Module::VERSION, $result->getModuleVersion());
        $this->assertSame(Module::SUPPORTED_OPERATIONS, $result->getSupportedOperations());
        $this->assertNotEmpty($result->getApiSchemaHash());
        $this->assertSame(16, strlen($result->getApiSchemaHash()));
    }

    public function testComputeSchemaHashIsDeterministic(): void
    {
        $ops = ['b', 'a', 'c'];

        $this->assertSame(
            ApiVersionService::computeSchemaHash($ops),
            ApiVersionService::computeSchemaHash($ops),
        );
    }

    public function testComputeSchemaHashIsOrderIndependent(): void
    {
        $this->assertSame(
            ApiVersionService::computeSchemaHash(['a', 'b', 'c']),
            ApiVersionService::computeSchemaHash(['c', 'a', 'b']),
        );
    }

    public function testComputeSchemaHashChangesWhenOperationsChange(): void
    {
        $hash1 = ApiVersionService::computeSchemaHash(['a', 'b']);
        $hash2 = ApiVersionService::computeSchemaHash(['a', 'b', 'c']);

        $this->assertNotSame($hash1, $hash2);
    }

    /**
     * Safeguard: Scans all GraphQL controller classes for #[Query] and #[Mutation]
     * attributes and verifies they match Module::SUPPORTED_OPERATIONS.
     *
     * If this test fails, you likely added/removed a GraphQL operation
     * but forgot to update Module::SUPPORTED_OPERATIONS.
     */
    public function testSupportedOperationsMatchActualGraphQLRoutes(): void
    {
        $controllerDir = dirname(__DIR__, 5) . '/src/Component';
        $actualOperations = self::EXTERNAL_OPERATIONS;

        $controllerFiles = glob($controllerDir . '/*/Controller/GraphQL/*.php');
        $this->assertNotEmpty($controllerFiles, 'No GraphQL controller files found');

        foreach ($controllerFiles as $file) {
            $className = $this->resolveClassName($file);

            if ($className === null || !class_exists($className)) {
                continue;
            }

            $reflection = new \ReflectionClass($className);

            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                $isQuery = !empty($method->getAttributes(Query::class));
                $isMutation = !empty($method->getAttributes(Mutation::class));

                if ($isQuery || $isMutation) {
                    $actualOperations[] = $method->getName();
                }
            }
        }

        sort($actualOperations);

        $expected = Module::SUPPORTED_OPERATIONS;
        sort($expected);

        $missing = array_diff($actualOperations, $expected);
        $extra = array_diff($expected, $actualOperations);

        $message = '';
        if (!empty($missing)) {
            $message .= 'Operations in controllers but missing from Module::SUPPORTED_OPERATIONS: '
                . implode(', ', $missing) . '. ';
        }
        if (!empty($extra)) {
            $message .= 'Operations in Module::SUPPORTED_OPERATIONS but not found in controllers: '
                . implode(', ', array_diff($extra, self::EXTERNAL_OPERATIONS)) . '. ';
        }

        $this->assertSame($expected, $actualOperations, $message);
    }

    private function resolveClassName(string $filePath): ?string
    {
        // Derive FQCN from path: .../src/Component/{Name}/Controller/GraphQL/{Class}.php
        if (!preg_match('#/src/Component/(\w+)/Controller/GraphQL/(\w+)\.php$#', $filePath, $matches)) {
            return null;
        }

        return sprintf(
            'OxidSupport\\Heartbeat\\Component\\%s\\Controller\\GraphQL\\%s',
            $matches[1],
            $matches[2],
        );
    }
}
