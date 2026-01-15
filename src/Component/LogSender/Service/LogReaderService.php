<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\LogSender\Service;

use OxidSupport\LoggingFramework\Component\LogSender\Exception\LogPathNotFoundException;
use SplFileObject;

/**
 * Service for reading log file contents.
 */
final class LogReaderService implements LogReaderServiceInterface
{
    private const DEFAULT_PATTERN = '*';
    private const MAX_FILES_IN_LISTING = 1000;

    /**
     * @inheritDoc
     */
    public function tail(string $path, int $lines = 100): string
    {
        if (!file_exists($path)) {
            throw new LogPathNotFoundException($path);
        }

        if (!is_readable($path)) {
            throw new LogPathNotFoundException($path);
        }

        $lines = max(1, $lines);

        // Handle empty files
        if (filesize($path) === 0) {
            return '';
        }

        // Efficient tail without loading the entire file
        $file = new SplFileObject($path, 'r');
        $file->seek(PHP_INT_MAX);
        $totalLines = $file->key();

        if ($totalLines === 0) {
            $file->rewind();
            return $file->fgets() ?: '';
        }

        $startLine = max(0, $totalLines - $lines);
        $result = [];

        $file->seek($startLine);
        while (!$file->eof()) {
            $line = $file->fgets();
            if ($line !== false) {
                $result[] = $line;
            }
        }

        return implode('', $result);
    }

    /**
     * @inheritDoc
     */
    public function listFiles(string $directory, ?string $pattern = null): array
    {
        if (!is_dir($directory)) {
            throw new LogPathNotFoundException($directory);
        }

        $pattern = $pattern ?? self::DEFAULT_PATTERN;
        $normalizedDir = rtrim($directory, '/\\');
        $globPattern = $normalizedDir . DIRECTORY_SEPARATOR . $pattern;

        $files = glob($globPattern);
        if ($files === false) {
            return [];
        }

        // Filter out directories, keep only files
        $files = array_filter($files, 'is_file');

        // Limit the number of files
        $files = array_slice($files, 0, self::MAX_FILES_IN_LISTING);

        // Sort by modification time (newest first)
        usort($files, function (string $a, string $b): int {
            return filemtime($b) <=> filemtime($a);
        });

        return array_map(function (string $file): array {
            return [
                'name' => basename($file),
                'path' => $file,
                'size' => filesize($file) ?: 0,
                'modified' => filemtime($file) ?: 0,
            ];
        }, $files);
    }

    /**
     * @inheritDoc
     */
    public function readFile(string $path, int $maxBytes = 1048576): string
    {
        if (!file_exists($path)) {
            throw new LogPathNotFoundException($path);
        }

        if (!is_readable($path)) {
            throw new LogPathNotFoundException($path);
        }

        $size = filesize($path);
        if ($size === false || $size === 0) {
            return '';
        }

        if ($size <= $maxBytes) {
            return file_get_contents($path) ?: '';
        }

        // Read only the last maxBytes
        $handle = fopen($path, 'r');
        if ($handle === false) {
            throw new LogPathNotFoundException($path);
        }

        fseek($handle, -$maxBytes, SEEK_END);
        $content = fread($handle, $maxBytes);
        fclose($handle);

        if ($content === false) {
            return '';
        }

        return "[...truncated...]\n" . $content;
    }

    /**
     * @inheritDoc
     */
    public function getFileInfo(string $path): array
    {
        if (!file_exists($path)) {
            throw new LogPathNotFoundException($path);
        }

        return [
            'name' => basename($path),
            'path' => $path,
            'size' => filesize($path) ?: 0,
            'modified' => filemtime($path) ?: 0,
            'readable' => is_readable($path),
        ];
    }
}
