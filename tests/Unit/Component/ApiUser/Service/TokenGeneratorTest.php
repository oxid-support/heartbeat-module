<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\ApiUser\Service;

use OxidSupport\Heartbeat\Component\ApiUser\Service\TokenGenerator;
use OxidSupport\Heartbeat\Component\ApiUser\Service\TokenGeneratorInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TokenGenerator::class)]
final class TokenGeneratorTest extends TestCase
{
    public function testImplementsTokenGeneratorInterface(): void
    {
        $reflection = new \ReflectionClass(TokenGenerator::class);

        $this->assertTrue($reflection->implementsInterface(TokenGeneratorInterface::class));
    }

    public function testClassIsFinal(): void
    {
        $reflection = new \ReflectionClass(TokenGenerator::class);

        $this->assertTrue($reflection->isFinal());
    }

    public function testGenerateMethodExists(): void
    {
        $reflection = new \ReflectionClass(TokenGenerator::class);

        $this->assertTrue($reflection->hasMethod('generate'));
    }

    public function testGenerateMethodIsPublic(): void
    {
        $reflection = new \ReflectionClass(TokenGenerator::class);
        $method = $reflection->getMethod('generate');

        $this->assertTrue($method->isPublic());
    }

    public function testGenerateMethodReturnsString(): void
    {
        $reflection = new \ReflectionClass(TokenGenerator::class);
        $method = $reflection->getMethod('generate');
        $returnType = $method->getReturnType();

        $this->assertNotNull($returnType);
        $this->assertEquals('string', $returnType->getName());
    }

    public function testGenerateMethodHasNoParameters(): void
    {
        $reflection = new \ReflectionClass(TokenGenerator::class);
        $method = $reflection->getMethod('generate');

        $this->assertCount(0, $method->getParameters());
    }

    public function testGenerateRequiresOxidFramework(): void
    {
        // This test documents that generate() requires OXID's Registry
        // In a real OXID environment, this would return a unique ID
        $this->markTestSkipped(
            'TokenGenerator::generate() requires OXID Registry'
            . ' which is not available in unit tests.'
        );
    }
}
