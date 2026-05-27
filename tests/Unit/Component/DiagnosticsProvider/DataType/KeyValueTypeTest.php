<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\DiagnosticsProvider\DataType;

use OxidSupport\Heartbeat\Component\DiagnosticsProvider\DataType\KeyValueType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

#[CoversClass(KeyValueType::class)]
final class KeyValueTypeTest extends TestCase
{
    public function testClassIsFinal(): void
    {
        $reflection = new ReflectionClass(KeyValueType::class);

        $this->assertTrue($reflection->isFinal());
    }

    public function testHasTypeAnnotation(): void
    {
        $reflection = new ReflectionClass(KeyValueType::class);

        $this->assertStringContainsString('@Type', $reflection->getDocComment());
    }

    public function testGetKeyReturnsString(): void
    {
        $type = new KeyValueType('testKey', 'testValue');

        $this->assertEquals('testKey', $type->getKey());
    }

    public function testGetValueReturnsString(): void
    {
        $type = new KeyValueType('testKey', 'testValue');

        $this->assertEquals('testValue', $type->getValue());
    }

    public function testGetKeyMethodExists(): void
    {
        $reflection = new ReflectionClass(KeyValueType::class);

        $this->assertTrue($reflection->hasMethod('getKey'));
    }

    public function testGetValueMethodExists(): void
    {
        $reflection = new ReflectionClass(KeyValueType::class);

        $this->assertTrue($reflection->hasMethod('getValue'));
    }

    public function testGetKeyHasFieldAnnotation(): void
    {
        $method = (new ReflectionClass(KeyValueType::class))->getMethod('getKey');

        $this->assertStringContainsString('@Field', $method->getDocComment());
    }

    public function testGetValueHasFieldAnnotation(): void
    {
        $method = (new ReflectionClass(KeyValueType::class))->getMethod('getValue');

        $this->assertStringContainsString('@Field', $method->getDocComment());
    }

    public function testGetKeyReturnsTypeString(): void
    {
        $reflection = new ReflectionClass(KeyValueType::class);
        $method = $reflection->getMethod('getKey');
        $returnType = $method->getReturnType();

        $this->assertNotNull($returnType);
        $this->assertEquals('string', $returnType->getName());
    }

    public function testGetValueReturnsTypeString(): void
    {
        $reflection = new ReflectionClass(KeyValueType::class);
        $method = $reflection->getMethod('getValue');
        $returnType = $method->getReturnType();

        $this->assertNotNull($returnType);
        $this->assertEquals('string', $returnType->getName());
    }

    public function testConstructorHasTwoParameters(): void
    {
        $reflection = new ReflectionClass(KeyValueType::class);
        $constructor = $reflection->getConstructor();

        $this->assertNotNull($constructor);
        $this->assertCount(2, $constructor->getParameters());
    }

    public function testEmptyKeyAndValue(): void
    {
        $type = new KeyValueType('', '');

        $this->assertEquals('', $type->getKey());
        $this->assertEquals('', $type->getValue());
    }

    public function testSpecialCharactersInKeyAndValue(): void
    {
        $type = new KeyValueType('key with spaces', 'value with "quotes"');

        $this->assertEquals('key with spaces', $type->getKey());
        $this->assertEquals('value with "quotes"', $type->getValue());
    }
}
