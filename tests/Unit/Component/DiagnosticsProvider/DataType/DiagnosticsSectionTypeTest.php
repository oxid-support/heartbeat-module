<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\DiagnosticsProvider\DataType;

use OxidSupport\Heartbeat\Component\DiagnosticsProvider\DataType\DiagnosticsSectionType;
use OxidSupport\Heartbeat\Component\DiagnosticsProvider\DataType\KeyValueType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

#[CoversClass(DiagnosticsSectionType::class)]
final class DiagnosticsSectionTypeTest extends TestCase
{
    public function testClassIsFinal(): void
    {
        $reflection = new ReflectionClass(DiagnosticsSectionType::class);

        $this->assertTrue($reflection->isFinal());
    }

    public function testHasTypeAttribute(): void
    {
        $reflection = new ReflectionClass(DiagnosticsSectionType::class);
        $attributes = $reflection->getAttributes();

        $attributeNames = array_map(fn($a) => $a->getName(), $attributes);
        $this->assertContains('TheCodingMachine\GraphQLite\Annotations\Type', $attributeNames);
    }

    public function testGetNameReturnsString(): void
    {
        $section = new DiagnosticsSectionType('Test Section', []);

        $this->assertEquals('Test Section', $section->getName());
    }

    public function testGetItemsReturnsArray(): void
    {
        $items = [
            new KeyValueType('key1', 'value1'),
            new KeyValueType('key2', 'value2'),
        ];
        $section = new DiagnosticsSectionType('Test Section', $items);

        $this->assertIsArray($section->getItems());
        $this->assertCount(2, $section->getItems());
    }

    public function testGetNameMethodExists(): void
    {
        $reflection = new ReflectionClass(DiagnosticsSectionType::class);

        $this->assertTrue($reflection->hasMethod('getName'));
    }

    public function testGetItemsMethodExists(): void
    {
        $reflection = new ReflectionClass(DiagnosticsSectionType::class);

        $this->assertTrue($reflection->hasMethod('getItems'));
    }

    public function testGetNameHasFieldAttribute(): void
    {
        $reflection = new ReflectionClass(DiagnosticsSectionType::class);
        $method = $reflection->getMethod('getName');
        $attributes = $method->getAttributes();

        $attributeNames = array_map(fn($a) => $a->getName(), $attributes);
        $this->assertContains('TheCodingMachine\GraphQLite\Annotations\Field', $attributeNames);
    }

    public function testGetItemsHasFieldAttribute(): void
    {
        $reflection = new ReflectionClass(DiagnosticsSectionType::class);
        $method = $reflection->getMethod('getItems');
        $attributes = $method->getAttributes();

        $attributeNames = array_map(fn($a) => $a->getName(), $attributes);
        $this->assertContains('TheCodingMachine\GraphQLite\Annotations\Field', $attributeNames);
    }

    public function testGetNameReturnsTypeString(): void
    {
        $reflection = new ReflectionClass(DiagnosticsSectionType::class);
        $method = $reflection->getMethod('getName');
        $returnType = $method->getReturnType();

        $this->assertNotNull($returnType);
        $this->assertEquals('string', $returnType->getName());
    }

    public function testGetItemsReturnsTypeArray(): void
    {
        $reflection = new ReflectionClass(DiagnosticsSectionType::class);
        $method = $reflection->getMethod('getItems');
        $returnType = $method->getReturnType();

        $this->assertNotNull($returnType);
        $this->assertEquals('array', $returnType->getName());
    }

    public function testFromArrayMethodExists(): void
    {
        $reflection = new ReflectionClass(DiagnosticsSectionType::class);

        $this->assertTrue($reflection->hasMethod('fromArray'));
    }

    public function testFromArrayIsStatic(): void
    {
        $reflection = new ReflectionClass(DiagnosticsSectionType::class);
        $method = $reflection->getMethod('fromArray');

        $this->assertTrue($method->isStatic());
    }

    public function testFromArrayCreatesSection(): void
    {
        $data = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];

        $section = DiagnosticsSectionType::fromArray('Test Section', $data);

        $this->assertEquals('Test Section', $section->getName());
        $this->assertCount(2, $section->getItems());
    }

    public function testFromArrayConvertsArrayValuesToJson(): void
    {
        $data = [
            'arrayKey' => ['nested' => 'value'],
        ];

        $section = DiagnosticsSectionType::fromArray('Test', $data);
        $items = $section->getItems();

        $this->assertEquals('{"nested":"value"}', $items[0]->getValue());
    }

    public function testFromArrayConvertsBoolTrueToString(): void
    {
        $data = [
            'boolKey' => true,
        ];

        $section = DiagnosticsSectionType::fromArray('Test', $data);
        $items = $section->getItems();

        $this->assertEquals('true', $items[0]->getValue());
    }

    public function testFromArrayConvertsBoolFalseToString(): void
    {
        $data = [
            'boolKey' => false,
        ];

        $section = DiagnosticsSectionType::fromArray('Test', $data);
        $items = $section->getItems();

        $this->assertEquals('false', $items[0]->getValue());
    }

    public function testFromArrayConvertsNullToString(): void
    {
        $data = [
            'nullKey' => null,
        ];

        $section = DiagnosticsSectionType::fromArray('Test', $data);
        $items = $section->getItems();

        $this->assertEquals('null', $items[0]->getValue());
    }

    public function testFromArrayConvertsNumericToString(): void
    {
        $data = [
            'intKey' => 123,
            'floatKey' => 1.5,
        ];

        $section = DiagnosticsSectionType::fromArray('Test', $data);
        $items = $section->getItems();

        $this->assertEquals('123', $items[0]->getValue());
        $this->assertEquals('1.5', $items[1]->getValue());
    }

    public function testEmptySection(): void
    {
        $section = new DiagnosticsSectionType('Empty', []);

        $this->assertEquals('Empty', $section->getName());
        $this->assertCount(0, $section->getItems());
    }

    public function testFromArrayWithEmptyData(): void
    {
        $section = DiagnosticsSectionType::fromArray('Empty', []);

        $this->assertEquals('Empty', $section->getName());
        $this->assertCount(0, $section->getItems());
    }

    public function testConstructorHasTwoParameters(): void
    {
        $reflection = new ReflectionClass(DiagnosticsSectionType::class);
        $constructor = $reflection->getConstructor();

        $this->assertNotNull($constructor);
        $this->assertCount(2, $constructor->getParameters());
    }
}
