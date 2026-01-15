<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Tests\Unit\Component\LogSender\DataType;

use OxidSupport\LoggingFramework\Component\LogSender\DataType\LogPathType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogPathType::class)]
final class LogPathTypeTest extends TestCase
{
    public function testFileTypeHasCorrectValue(): void
    {
        $this->assertEquals('file', LogPathType::FILE->value);
    }

    public function testDirectoryTypeHasCorrectValue(): void
    {
        $this->assertEquals('directory', LogPathType::DIRECTORY->value);
    }

    public function testFileLabelReturnsCorrectString(): void
    {
        $this->assertEquals('File', LogPathType::FILE->getLabel());
    }

    public function testDirectoryLabelReturnsCorrectString(): void
    {
        $this->assertEquals('Directory', LogPathType::DIRECTORY->getLabel());
    }

    public function testFileLabelDeReturnsCorrectString(): void
    {
        $this->assertEquals('Datei', LogPathType::FILE->getLabelDe());
    }

    public function testDirectoryLabelDeReturnsCorrectString(): void
    {
        $this->assertEquals('Verzeichnis', LogPathType::DIRECTORY->getLabelDe());
    }

    public function testCanCreateFromValidValue(): void
    {
        $file = LogPathType::from('file');
        $directory = LogPathType::from('directory');

        $this->assertSame(LogPathType::FILE, $file);
        $this->assertSame(LogPathType::DIRECTORY, $directory);
    }

    public function testTryFromReturnsNullForInvalidValue(): void
    {
        $result = LogPathType::tryFrom('invalid');

        $this->assertNull($result);
    }

    public function testEnumHasExactlyTwoCases(): void
    {
        $cases = LogPathType::cases();

        $this->assertCount(2, $cases);
    }

    public function testEnumIsBackedByString(): void
    {
        $reflection = new \ReflectionEnum(LogPathType::class);

        $this->assertTrue($reflection->isBacked());
        $this->assertEquals('string', $reflection->getBackingType()->getName());
    }
}
