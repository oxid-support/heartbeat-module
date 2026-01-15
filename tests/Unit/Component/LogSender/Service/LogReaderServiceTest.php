<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Tests\Unit\Component\LogSender\Service;

use OxidSupport\LoggingFramework\Component\LogSender\Exception\LogPathNotFoundException;
use OxidSupport\LoggingFramework\Component\LogSender\Service\LogReaderService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogReaderService::class)]
final class LogReaderServiceTest extends TestCase
{
    private LogReaderService $service;
    private string $testDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LogReaderService();
        $this->testDir = sys_get_temp_dir() . '/logsender_test_' . uniqid();
        mkdir($this->testDir, 0777, true);
    }

    protected function tearDown(): void
    {
        // Clean up test files
        $files = glob($this->testDir . '/*');
        if ($files) {
            foreach ($files as $file) {
                unlink($file);
            }
        }
        if (is_dir($this->testDir)) {
            rmdir($this->testDir);
        }
        parent::tearDown();
    }

    private function createTestFile(string $filename, string $content): string
    {
        $path = $this->testDir . '/' . $filename;
        file_put_contents($path, $content);
        return $path;
    }

    // tail() tests

    public function testTailThrowsExceptionForNonExistentFile(): void
    {
        $this->expectException(LogPathNotFoundException::class);

        $this->service->tail('/nonexistent/file.log');
    }

    public function testTailReturnsEmptyStringForEmptyFile(): void
    {
        $path = $this->createTestFile('empty.log', '');

        $result = $this->service->tail($path);

        $this->assertEquals('', $result);
    }

    public function testTailReturnsFullContentForSmallFile(): void
    {
        $content = "Line 1\nLine 2\nLine 3\n";
        $path = $this->createTestFile('small.log', $content);

        $result = $this->service->tail($path, 100);

        $this->assertEquals($content, $result);
    }

    public function testTailReturnsLastNLines(): void
    {
        $content = "Line 1\nLine 2\nLine 3\nLine 4\nLine 5\n";
        $path = $this->createTestFile('lines.log', $content);

        $result = $this->service->tail($path, 2);

        $this->assertStringContainsString('Line 4', $result);
        $this->assertStringContainsString('Line 5', $result);
    }

    public function testTailDefaultsTo100Lines(): void
    {
        $lines = [];
        for ($i = 1; $i <= 150; $i++) {
            $lines[] = "Line $i";
        }
        $content = implode("\n", $lines) . "\n";
        $path = $this->createTestFile('many.log', $content);

        $result = $this->service->tail($path);

        // Should contain lines 51-150 (last 100 lines)
        $this->assertStringContainsString('Line 150', $result);
        $this->assertStringNotContainsString('Line 50', $result);
    }

    // listFiles() tests

    public function testListFilesThrowsExceptionForNonExistentDirectory(): void
    {
        $this->expectException(LogPathNotFoundException::class);

        $this->service->listFiles('/nonexistent/directory');
    }

    public function testListFilesReturnsEmptyArrayForEmptyDirectory(): void
    {
        $result = $this->service->listFiles($this->testDir);

        $this->assertEquals([], $result);
    }

    public function testListFilesReturnsFilesWithCorrectStructure(): void
    {
        $path = $this->createTestFile('test.log', 'content');

        $result = $this->service->listFiles($this->testDir, '*.log');

        $this->assertCount(1, $result);
        $this->assertEquals('test.log', $result[0]['name']);
        $this->assertEquals($path, $result[0]['path']);
        $this->assertArrayHasKey('size', $result[0]);
        $this->assertArrayHasKey('modified', $result[0]);
    }

    public function testListFilesFiltersFilesByPattern(): void
    {
        $this->createTestFile('test.log', 'content');
        $this->createTestFile('test.txt', 'content');

        $result = $this->service->listFiles($this->testDir, '*.log');

        $this->assertCount(1, $result);
        $this->assertEquals('test.log', $result[0]['name']);
    }

    public function testListFilesSortsByModificationTimeDescending(): void
    {
        $path1 = $this->createTestFile('old.log', 'old content');
        sleep(1); // Ensure different modification times
        $path2 = $this->createTestFile('new.log', 'new content');

        $result = $this->service->listFiles($this->testDir, '*.log');

        $this->assertCount(2, $result);
        $this->assertEquals('new.log', $result[0]['name']);
        $this->assertEquals('old.log', $result[1]['name']);
    }

    // readFile() tests

    public function testReadFileThrowsExceptionForNonExistentFile(): void
    {
        $this->expectException(LogPathNotFoundException::class);

        $this->service->readFile('/nonexistent/file.log');
    }

    public function testReadFileReturnsEmptyStringForEmptyFile(): void
    {
        $path = $this->createTestFile('empty.log', '');

        $result = $this->service->readFile($path);

        $this->assertEquals('', $result);
    }

    public function testReadFileReturnsFullContentForSmallFile(): void
    {
        $content = str_repeat('x', 1000);
        $path = $this->createTestFile('small.log', $content);

        $result = $this->service->readFile($path);

        $this->assertEquals($content, $result);
    }

    public function testReadFileTruncatesLargeFiles(): void
    {
        $content = str_repeat('x', 2000);
        $path = $this->createTestFile('large.log', $content);

        $result = $this->service->readFile($path, 500);

        $this->assertStringStartsWith('[...truncated...]', $result);
        $this->assertLessThan(2000, strlen($result));
    }

    // getFileInfo() tests

    public function testGetFileInfoThrowsExceptionForNonExistentFile(): void
    {
        $this->expectException(LogPathNotFoundException::class);

        $this->service->getFileInfo('/nonexistent/file.log');
    }

    public function testGetFileInfoReturnsCorrectStructure(): void
    {
        $path = $this->createTestFile('info.log', 'test content');

        $result = $this->service->getFileInfo($path);

        $this->assertEquals('info.log', $result['name']);
        $this->assertEquals($path, $result['path']);
        $this->assertEquals(12, $result['size']); // 'test content' = 12 bytes
        $this->assertArrayHasKey('modified', $result);
        $this->assertTrue($result['readable']);
    }

    // Service class tests

    public function testServiceImplementsInterface(): void
    {
        $this->assertInstanceOf(
            \OxidSupport\LoggingFramework\Component\LogSender\Service\LogReaderServiceInterface::class,
            $this->service
        );
    }

    public function testClassIsFinal(): void
    {
        $reflection = new \ReflectionClass(LogReaderService::class);

        $this->assertTrue($reflection->isFinal());
    }
}
