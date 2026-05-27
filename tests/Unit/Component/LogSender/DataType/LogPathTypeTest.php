<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\LogSender\DataType;

use OxidSupport\Heartbeat\Component\LogSender\DataType\LogPathType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogPathType::class)]
final class LogPathTypeTest extends TestCase
{
    public function testFileTypeHasCorrectValue(): void
    {
        $this->assertEquals('file', LogPathType::FILE()->value);
    }

    public function testDirectoryTypeHasCorrectValue(): void
    {
        $this->assertEquals('directory', LogPathType::DIRECTORY()->value);
    }

    public function testFileLabelReturnsCorrectString(): void
    {
        $this->assertEquals('File', LogPathType::FILE()->getLabel());
    }

    public function testDirectoryLabelReturnsCorrectString(): void
    {
        $this->assertEquals('Directory', LogPathType::DIRECTORY()->getLabel());
    }

    public function testFileLabelDeReturnsCorrectString(): void
    {
        $this->assertEquals('Datei', LogPathType::FILE()->getLabelDe());
    }

    public function testDirectoryLabelDeReturnsCorrectString(): void
    {
        $this->assertEquals('Verzeichnis', LogPathType::DIRECTORY()->getLabelDe());
    }

    public function testTryFromReturnsCorrectInstances(): void
    {
        $file = LogPathType::tryFrom('file');
        $directory = LogPathType::tryFrom('directory');

        $this->assertSame(LogPathType::FILE(), $file);
        $this->assertSame(LogPathType::DIRECTORY(), $directory);
    }

    public function testTryFromReturnsNullForInvalidValue(): void
    {
        $result = LogPathType::tryFrom('invalid');

        $this->assertNull($result);
    }

    public function testSingletonIdentity(): void
    {
        $this->assertSame(LogPathType::FILE(), LogPathType::FILE());
        $this->assertSame(LogPathType::DIRECTORY(), LogPathType::DIRECTORY());
        $this->assertNotSame(LogPathType::FILE(), LogPathType::DIRECTORY());
    }
}
