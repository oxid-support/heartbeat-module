<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\LogSender\Service;

use OxidSupport\LoggingFramework\Component\LogSender\Exception\LogPathNotFoundException;

/**
 * Service for reading log file contents.
 */
interface LogReaderServiceInterface
{
    /**
     * Reads the last N lines from a log file (tail).
     *
     * @param string $path Absolute path to the log file
     * @param int $lines Number of lines to read from the end
     * @return string The last N lines of the file
     * @throws LogPathNotFoundException If the file does not exist
     */
    public function tail(string $path, int $lines = 100): string;

    /**
     * Lists files in a log directory.
     *
     * @param string $directory Absolute path to the directory
     * @param string|null $pattern Optional glob pattern (e.g., "*.log")
     * @return array<array{name: string, path: string, size: int, modified: int}>
     * @throws LogPathNotFoundException If the directory does not exist
     */
    public function listFiles(string $directory, ?string $pattern = null): array;

    /**
     * Reads a complete log file (with size limit).
     *
     * @param string $path Absolute path to the log file
     * @param int $maxBytes Maximum bytes to read (reads last N bytes if file is larger)
     * @return string The file content (possibly truncated)
     * @throws LogPathNotFoundException If the file does not exist
     */
    public function readFile(string $path, int $maxBytes = 1048576): string;

    /**
     * Returns information about a log file.
     *
     * @param string $path Absolute path to the log file
     * @return array{name: string, path: string, size: int, modified: int, readable: bool}
     * @throws LogPathNotFoundException If the file does not exist
     */
    public function getFileInfo(string $path): array;
}
