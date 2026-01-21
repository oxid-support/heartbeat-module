<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\DiagnosticsProvider\Framework;

use OxidEsales\GraphQL\Base\Framework\NamespaceMapperInterface;
use OxidSupport\Heartbeat\Component\DiagnosticsProvider\Framework\NamespaceMapper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(NamespaceMapper::class)]
final class NamespaceMapperTest extends TestCase
{
    public function testImplementsNamespaceMapperInterface(): void
    {
        $mapper = new NamespaceMapper();

        $this->assertInstanceOf(NamespaceMapperInterface::class, $mapper);
    }

    public function testGetControllerNamespaceMappingReturnsArray(): void
    {
        $mapper = new NamespaceMapper();

        $this->assertIsArray($mapper->getControllerNamespaceMapping());
    }

    public function testGetControllerNamespaceMappingContainsGraphQLControllerNamespace(): void
    {
        $mapper = new NamespaceMapper();
        $mapping = $mapper->getControllerNamespaceMapping();

        $this->assertArrayHasKey(
            'OxidSupport\\Heartbeat\\Component\\DiagnosticsProvider\\Controller\\GraphQL',
            $mapping
        );
    }

    public function testGetControllerNamespaceMappingPointsToValidDirectory(): void
    {
        $mapper = new NamespaceMapper();
        $mapping = $mapper->getControllerNamespaceMapping();

        $path = $mapping['OxidSupport\\Heartbeat\\Component\\DiagnosticsProvider\\Controller\\GraphQL'];
        $this->assertDirectoryExists($path);
    }

    public function testGetTypeNamespaceMappingReturnsArray(): void
    {
        $mapper = new NamespaceMapper();

        $this->assertIsArray($mapper->getTypeNamespaceMapping());
    }

    public function testGetTypeNamespaceMappingContainsDataTypeNamespace(): void
    {
        $mapper = new NamespaceMapper();
        $mapping = $mapper->getTypeNamespaceMapping();

        $this->assertArrayHasKey(
            'OxidSupport\\Heartbeat\\Component\\DiagnosticsProvider\\DataType',
            $mapping
        );
    }

    public function testGetTypeNamespaceMappingPointsToValidDirectory(): void
    {
        $mapper = new NamespaceMapper();
        $mapping = $mapper->getTypeNamespaceMapping();

        $path = $mapping['OxidSupport\\Heartbeat\\Component\\DiagnosticsProvider\\DataType'];
        $this->assertDirectoryExists($path);
    }

    public function testReturnsOnlyOneControllerNamespace(): void
    {
        $mapper = new NamespaceMapper();

        $this->assertCount(1, $mapper->getControllerNamespaceMapping());
    }

    public function testReturnsOnlyOneTypeNamespace(): void
    {
        $mapper = new NamespaceMapper();

        $this->assertCount(1, $mapper->getTypeNamespaceMapping());
    }

    public function testClassIsFinal(): void
    {
        $reflection = new \ReflectionClass(NamespaceMapper::class);

        $this->assertTrue($reflection->isFinal());
    }
}
