<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Component\LogSender\DataType;

use OxidSupport\Heartbeat\Component\LogSender\DataType\LogPath;
use OxidSupport\Heartbeat\Component\LogSender\DataType\LogPathType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogPath::class)]
final class LogPathTest extends TestCase
{
    public function testConstructorSetsPath(): void
    {
        $logPath = new LogPath('/var/log/test.log', LogPathType::FILE(), 'Test Log');

        $this->assertEquals('/var/log/test.log', $logPath->path);
    }

    public function testConstructorSetsType(): void
    {
        $logPath = new LogPath('/var/log/test.log', LogPathType::FILE(), 'Test Log');

        $this->assertSame(LogPathType::FILE(), $logPath->type);
    }

    public function testConstructorSetsName(): void
    {
        $logPath = new LogPath('/var/log/test.log', LogPathType::FILE(), 'Test Log');

        $this->assertEquals('Test Log', $logPath->name);
    }

    public function testConstructorSetsDescription(): void
    {
        $logPath = new LogPath('/var/log/test.log', LogPathType::FILE(), 'Test Log', 'A test description');

        $this->assertEquals('A test description', $logPath->description);
    }

    public function testConstructorSetsDefaultDescription(): void
    {
        $logPath = new LogPath('/var/log/test.log', LogPathType::FILE(), 'Test Log');

        $this->assertEquals('', $logPath->description);
    }

    public function testConstructorSetsFilePattern(): void
    {
        $logPath = new LogPath('/var/log/', LogPathType::DIRECTORY(), 'Logs', '', '*.log');

        $this->assertEquals('*.log', $logPath->filePattern);
    }

    public function testConstructorSetsDefaultFilePattern(): void
    {
        $logPath = new LogPath('/var/log/', LogPathType::DIRECTORY(), 'Logs');

        $this->assertNull($logPath->filePattern);
    }

    public function testIsDirectoryReturnsTrueForDirectory(): void
    {
        $logPath = new LogPath('/var/log/', LogPathType::DIRECTORY(), 'Logs');

        $this->assertTrue($logPath->isDirectory());
    }

    public function testIsDirectoryReturnsFalseForFile(): void
    {
        $logPath = new LogPath('/var/log/test.log', LogPathType::FILE(), 'Test Log');

        $this->assertFalse($logPath->isDirectory());
    }

    public function testIsFileReturnsTrueForFile(): void
    {
        $logPath = new LogPath('/var/log/test.log', LogPathType::FILE(), 'Test Log');

        $this->assertTrue($logPath->isFile());
    }

    public function testIsFileReturnsFalseForDirectory(): void
    {
        $logPath = new LogPath('/var/log/', LogPathType::DIRECTORY(), 'Logs');

        $this->assertFalse($logPath->isFile());
    }

    public function testExistsReturnsFalseForNonExistentFile(): void
    {
        $logPath = new LogPath('/nonexistent/path/file.log', LogPathType::FILE(), 'Test');

        $this->assertFalse($logPath->exists());
    }

    public function testExistsReturnsFalseForNonExistentDirectory(): void
    {
        $logPath = new LogPath('/nonexistent/path/', LogPathType::DIRECTORY(), 'Test');

        $this->assertFalse($logPath->exists());
    }

    public function testExistsReturnsTrueForExistingDirectory(): void
    {
        $logPath = new LogPath(sys_get_temp_dir(), LogPathType::DIRECTORY(), 'Temp');

        $this->assertTrue($logPath->exists());
    }

    public function testIsReadableReturnsFalseForNonExistent(): void
    {
        $logPath = new LogPath('/nonexistent/path/file.log', LogPathType::FILE(), 'Test');

        $this->assertFalse($logPath->isReadable());
    }

    public function testIsReadableReturnsTrueForExistingReadable(): void
    {
        $logPath = new LogPath(sys_get_temp_dir(), LogPathType::DIRECTORY(), 'Temp');

        $this->assertTrue($logPath->isReadable());
    }

    public function testGetNormalizedPathRemovesTrailingSlash(): void
    {
        $logPath = new LogPath('/var/log/myapp/', LogPathType::DIRECTORY(), 'Logs');

        $this->assertEquals('/var/log/myapp', $logPath->getNormalizedPath());
    }

    public function testGetNormalizedPathPreservesPathWithoutTrailingSlash(): void
    {
        $logPath = new LogPath('/var/log/test.log', LogPathType::FILE(), 'Test');

        $this->assertEquals('/var/log/test.log', $logPath->getNormalizedPath());
    }

    public function testToArrayReturnsCorrectStructure(): void
    {
        $logPath = new LogPath('/var/log/test.log', LogPathType::FILE(), 'Test Log', 'Description', '*.log');

        $array = $logPath->toArray();

        $this->assertEquals('/var/log/test.log', $array['path']);
        $this->assertEquals('file', $array['type']);
        $this->assertEquals('Test Log', $array['name']);
        $this->assertEquals('Description', $array['description']);
        $this->assertEquals('*.log', $array['filePattern']);
        $this->assertArrayHasKey('validation', $array);
    }

    // getValidation() tests

    public function testGetValidationReturnsPathNotFoundForNonExistentFile(): void
    {
        $logPath = new LogPath('/nonexistent/path/file.log', LogPathType::FILE(), 'Test');

        $validation = $logPath->getValidation();

        $this->assertFalse($validation['exists']);
        $this->assertFalse($validation['readable']);
        $this->assertEquals('error', $validation['status']);
        $this->assertEquals('PATH_NOT_FOUND', $validation['error']);
    }

    public function testGetValidationReturnsPathNotFoundForNonExistentDirectory(): void
    {
        $logPath = new LogPath('/nonexistent/path/', LogPathType::DIRECTORY(), 'Test');

        $validation = $logPath->getValidation();

        $this->assertFalse($validation['exists']);
        $this->assertFalse($validation['readable']);
        $this->assertEquals('error', $validation['status']);
        $this->assertEquals('PATH_NOT_FOUND', $validation['error']);
    }

    public function testGetValidationReturnsOkStatusForExistingReadableDirectory(): void
    {
        $logPath = new LogPath(sys_get_temp_dir(), LogPathType::DIRECTORY(), 'Temp');

        $validation = $logPath->getValidation();

        $this->assertTrue($validation['exists']);
        $this->assertTrue($validation['readable']);
        $this->assertEquals('ok', $validation['status']);
        $this->assertNull($validation['error']);
        $this->assertIsInt($validation['fileCount']);
        $this->assertNull($validation['fileSize']);
    }

    public function testGetValidationReturnsOkStatusForExistingReadableFile(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'logpath_test_');
        file_put_contents($tempFile, 'test content');

        try {
            $logPath = new LogPath($tempFile, LogPathType::FILE(), 'Test');

            $validation = $logPath->getValidation();

            $this->assertTrue($validation['exists']);
            $this->assertTrue($validation['readable']);
            $this->assertEquals('ok', $validation['status']);
            $this->assertNull($validation['error']);
            $this->assertNull($validation['fileCount']);
            $this->assertEquals(12, $validation['fileSize']); // 'test content' = 12 bytes
        } finally {
            unlink($tempFile);
        }
    }

    public function testGetValidationReturnsFileCountForDirectory(): void
    {
        $tempDir = sys_get_temp_dir() . '/logpath_test_' . uniqid();
        mkdir($tempDir);
        touch($tempDir . '/file1.log');
        touch($tempDir . '/file2.log');

        try {
            $logPath = new LogPath($tempDir, LogPathType::DIRECTORY(), 'Test');

            $validation = $logPath->getValidation();

            $this->assertEquals(2, $validation['fileCount']);
        } finally {
            unlink($tempDir . '/file1.log');
            unlink($tempDir . '/file2.log');
            rmdir($tempDir);
        }
    }

    public function testGetValidationReturnsFileSizeForFile(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'logpath_test_');
        file_put_contents($tempFile, str_repeat('x', 1024)); // 1KB

        try {
            $logPath = new LogPath($tempFile, LogPathType::FILE(), 'Test');

            $validation = $logPath->getValidation();

            $this->assertEquals(1024, $validation['fileSize']);
        } finally {
            unlink($tempFile);
        }
    }

    public function testClassIsFinal(): void
    {
        $reflection = new \ReflectionClass(LogPath::class);

        $this->assertTrue($reflection->isFinal());
    }
}
