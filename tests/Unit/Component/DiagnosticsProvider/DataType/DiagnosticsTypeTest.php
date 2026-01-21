<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\DiagnosticsProvider\DataType;

use OxidSupport\Heartbeat\Component\DiagnosticsProvider\DataType\DiagnosticsSectionType;
use OxidSupport\Heartbeat\Component\DiagnosticsProvider\DataType\DiagnosticsType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

#[CoversClass(DiagnosticsType::class)]
final class DiagnosticsTypeTest extends TestCase
{
    public function testClassIsFinal(): void
    {
        $reflection = new ReflectionClass(DiagnosticsType::class);

        $this->assertTrue($reflection->isFinal());
    }

    public function testHasTypeAttribute(): void
    {
        $reflection = new ReflectionClass(DiagnosticsType::class);
        $attributes = $reflection->getAttributes();

        $attributeNames = array_map(fn($a) => $a->getName(), $attributes);
        $this->assertContains('TheCodingMachine\GraphQLite\Annotations\Type', $attributeNames);
    }

    public function testGetSectionsReturnsArray(): void
    {
        $sections = [
            DiagnosticsSectionType::fromArray('Section1', ['key' => 'value']),
        ];
        $type = new DiagnosticsType($sections, 'decoder');

        $this->assertIsArray($type->getSections());
        $this->assertCount(1, $type->getSections());
    }

    public function testGetPhpDecoderReturnsString(): void
    {
        $type = new DiagnosticsType([], 'test-decoder');

        $this->assertEquals('test-decoder', $type->getPhpDecoder());
    }

    public function testGetSectionsMethodExists(): void
    {
        $reflection = new ReflectionClass(DiagnosticsType::class);

        $this->assertTrue($reflection->hasMethod('getSections'));
    }

    public function testGetPhpDecoderMethodExists(): void
    {
        $reflection = new ReflectionClass(DiagnosticsType::class);

        $this->assertTrue($reflection->hasMethod('getPhpDecoder'));
    }

    public function testGetSectionsHasFieldAttribute(): void
    {
        $reflection = new ReflectionClass(DiagnosticsType::class);
        $method = $reflection->getMethod('getSections');
        $attributes = $method->getAttributes();

        $attributeNames = array_map(fn($a) => $a->getName(), $attributes);
        $this->assertContains('TheCodingMachine\GraphQLite\Annotations\Field', $attributeNames);
    }

    public function testGetPhpDecoderHasFieldAttribute(): void
    {
        $reflection = new ReflectionClass(DiagnosticsType::class);
        $method = $reflection->getMethod('getPhpDecoder');
        $attributes = $method->getAttributes();

        $attributeNames = array_map(fn($a) => $a->getName(), $attributes);
        $this->assertContains('TheCodingMachine\GraphQLite\Annotations\Field', $attributeNames);
    }

    public function testGetSectionsReturnsTypeArray(): void
    {
        $reflection = new ReflectionClass(DiagnosticsType::class);
        $method = $reflection->getMethod('getSections');
        $returnType = $method->getReturnType();

        $this->assertNotNull($returnType);
        $this->assertEquals('array', $returnType->getName());
    }

    public function testGetPhpDecoderReturnsTypeString(): void
    {
        $reflection = new ReflectionClass(DiagnosticsType::class);
        $method = $reflection->getMethod('getPhpDecoder');
        $returnType = $method->getReturnType();

        $this->assertNotNull($returnType);
        $this->assertEquals('string', $returnType->getName());
    }

    public function testFromDiagnosticsArrayMethodExists(): void
    {
        $reflection = new ReflectionClass(DiagnosticsType::class);

        $this->assertTrue($reflection->hasMethod('fromDiagnosticsArray'));
    }

    public function testFromDiagnosticsArrayIsStatic(): void
    {
        $reflection = new ReflectionClass(DiagnosticsType::class);
        $method = $reflection->getMethod('fromDiagnosticsArray');

        $this->assertTrue($method->isStatic());
    }

    public function testFromDiagnosticsArrayCreatesDiagnostics(): void
    {
        $data = [
            'sPhpDecoder' => 'test-decoder',
            'aShopDetails' => ['url' => 'http://test.com'],
            'aModuleList' => ['module1' => 'active'],
            'aInfo' => ['info1' => 'value1'],
            'aCollations' => ['collation1' => 'utf8'],
            'aPhpConfigparams' => ['param1' => 'value1'],
            'aServerInfo' => ['server' => 'apache'],
        ];

        $diagnostics = DiagnosticsType::fromDiagnosticsArray($data);

        $this->assertEquals('test-decoder', $diagnostics->getPhpDecoder());
        $this->assertCount(6, $diagnostics->getSections());
    }

    public function testFromDiagnosticsArrayHandlesMissingPhpDecoder(): void
    {
        $data = [
            'aShopDetails' => ['url' => 'http://test.com'],
        ];

        $diagnostics = DiagnosticsType::fromDiagnosticsArray($data);

        $this->assertEquals('', $diagnostics->getPhpDecoder());
    }

    public function testFromDiagnosticsArraySkipsNonArraySections(): void
    {
        $data = [
            'sPhpDecoder' => 'decoder',
            'aShopDetails' => 'not-an-array',
            'aModuleList' => ['module1' => 'active'],
        ];

        $diagnostics = DiagnosticsType::fromDiagnosticsArray($data);

        // Should only have 1 section (aModuleList), not aShopDetails
        $this->assertCount(1, $diagnostics->getSections());
    }

    public function testFromDiagnosticsArrayCreatesCorrectSectionNames(): void
    {
        $data = [
            'aShopDetails' => ['key' => 'value'],
            'aModuleList' => ['key' => 'value'],
            'aInfo' => ['key' => 'value'],
            'aCollations' => ['key' => 'value'],
            'aPhpConfigparams' => ['key' => 'value'],
            'aServerInfo' => ['key' => 'value'],
        ];

        $diagnostics = DiagnosticsType::fromDiagnosticsArray($data);
        $sections = $diagnostics->getSections();

        $sectionNames = array_map(fn($s) => $s->getName(), $sections);

        $this->assertContains('Shop Details', $sectionNames);
        $this->assertContains('Module List', $sectionNames);
        $this->assertContains('System Information', $sectionNames);
        $this->assertContains('Database Collations', $sectionNames);
        $this->assertContains('PHP Configuration', $sectionNames);
        $this->assertContains('Server Information', $sectionNames);
    }

    public function testEmptySections(): void
    {
        $type = new DiagnosticsType([], '');

        $this->assertCount(0, $type->getSections());
        $this->assertEquals('', $type->getPhpDecoder());
    }

    public function testFromDiagnosticsArrayWithEmptyData(): void
    {
        $diagnostics = DiagnosticsType::fromDiagnosticsArray([]);

        $this->assertEquals('', $diagnostics->getPhpDecoder());
        $this->assertCount(0, $diagnostics->getSections());
    }

    public function testConstructorHasTwoParameters(): void
    {
        $reflection = new ReflectionClass(DiagnosticsType::class);
        $constructor = $reflection->getConstructor();

        $this->assertNotNull($constructor);
        $this->assertCount(2, $constructor->getParameters());
    }
}
