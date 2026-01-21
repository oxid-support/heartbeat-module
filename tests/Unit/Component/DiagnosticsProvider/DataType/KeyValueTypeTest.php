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

    public function testHasTypeAttribute(): void
    {
        $reflection = new ReflectionClass(KeyValueType::class);
        $attributes = $reflection->getAttributes();

        $attributeNames = array_map(fn($a) => $a->getName(), $attributes);
        $this->assertContains('TheCodingMachine\GraphQLite\Annotations\Type', $attributeNames);
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

    public function testGetKeyHasFieldAttribute(): void
    {
        $reflection = new ReflectionClass(KeyValueType::class);
        $method = $reflection->getMethod('getKey');
        $attributes = $method->getAttributes();

        $attributeNames = array_map(fn($a) => $a->getName(), $attributes);
        $this->assertContains('TheCodingMachine\GraphQLite\Annotations\Field', $attributeNames);
    }

    public function testGetValueHasFieldAttribute(): void
    {
        $reflection = new ReflectionClass(KeyValueType::class);
        $method = $reflection->getMethod('getValue');
        $attributes = $method->getAttributes();

        $attributeNames = array_map(fn($a) => $a->getName(), $attributes);
        $this->assertContains('TheCodingMachine\GraphQLite\Annotations\Field', $attributeNames);
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
