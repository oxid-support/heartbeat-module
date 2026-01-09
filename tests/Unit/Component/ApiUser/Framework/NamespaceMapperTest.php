<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Tests\Unit\Component\ApiUser\Framework;

use OxidEsales\GraphQL\Base\Framework\NamespaceMapperInterface;
use OxidSupport\LoggingFramework\Component\ApiUser\Framework\NamespaceMapper;
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
        $mapping = $mapper->getControllerNamespaceMapping();

        $this->assertIsArray($mapping);
    }

    public function testGetControllerNamespaceMappingContainsGraphQLControllerNamespace(): void
    {
        $mapper = new NamespaceMapper();
        $mapping = $mapper->getControllerNamespaceMapping();

        $this->assertArrayHasKey(
            'OxidSupport\\LoggingFramework\\Component\\ApiUser\\Controller\\GraphQL',
            $mapping
        );
    }

    public function testGetControllerNamespaceMappingPointsToValidDirectory(): void
    {
        $mapper = new NamespaceMapper();
        $mapping = $mapper->getControllerNamespaceMapping();

        $path = $mapping['OxidSupport\\LoggingFramework\\Component\\ApiUser\\Controller\\GraphQL'];

        $this->assertDirectoryExists($path);
    }

    public function testGetTypeNamespaceMappingReturnsEmptyArray(): void
    {
        $mapper = new NamespaceMapper();
        $mapping = $mapper->getTypeNamespaceMapping();

        $this->assertIsArray($mapping);
        $this->assertEmpty($mapping);
    }
}
