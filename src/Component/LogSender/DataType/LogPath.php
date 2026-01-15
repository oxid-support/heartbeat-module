<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\LogSender\DataType;

/**
 * Value object representing a log path configuration.
 * Contains information about where logs are located and how to access them.
 */
final class LogPath
{
    public function __construct(
        /** Absolute path to the file or directory */
        public readonly string $path,

        /** Type: FILE or DIRECTORY */
        public readonly LogPathType $type,

        /** Display name for UI/API */
        public readonly string $name,

        /** Optional description */
        public readonly string $description = '',

        /** Optional glob pattern for directories (e.g., "*.log") */
        public readonly ?string $filePattern = null,
    ) {
    }

    public function isDirectory(): bool
    {
        return $this->type === LogPathType::DIRECTORY;
    }

    public function isFile(): bool
    {
        return $this->type === LogPathType::FILE;
    }

    public function exists(): bool
    {
        return $this->isDirectory()
            ? is_dir($this->path)
            : file_exists($this->path);
    }

    public function isReadable(): bool
    {
        return $this->exists() && is_readable($this->path);
    }

    /**
     * Returns the normalized path (without trailing slash for directories).
     */
    public function getNormalizedPath(): string
    {
        return rtrim($this->path, '/\\');
    }

    /**
     * Converts the LogPath to an array representation including validation data.
     *
     * @return array{path: string, type: string, name: string, description: string, filePattern: string|null, validation: array}
     */
    public function toArray(): array
    {
        return [
            'path' => $this->path,
            'type' => $this->type->value,
            'name' => $this->name,
            'description' => $this->description,
            'filePattern' => $this->filePattern,
            'validation' => $this->getValidation(),
        ];
    }

    /**
     * Get validation status for this path.
     *
     * @return array{exists: bool, readable: bool, status: string, error: string|null, fileSize: int|null, fileCount: int|null}
     */
    public function getValidation(): array
    {
        $result = [
            'exists' => false,
            'readable' => false,
            'status' => 'error',
            'error' => null,
            'fileSize' => null,
            'fileCount' => null,
        ];

        if (!$this->exists()) {
            $result['error'] = 'PATH_NOT_FOUND';
            return $result;
        }

        $result['exists'] = true;

        if (!$this->isReadable()) {
            $result['error'] = 'NOT_READABLE';
            return $result;
        }

        $result['readable'] = true;

        if ($this->isDirectory()) {
            $files = @scandir($this->path);
            if ($files === false) {
                $result['error'] = 'CANNOT_LIST_DIRECTORY';
                $result['status'] = 'warning';
                return $result;
            }
            $result['fileCount'] = count(array_filter($files, fn($f) => $f !== '.' && $f !== '..'));
        } else {
            $result['fileSize'] = filesize($this->path) ?: 0;
        }

        $result['status'] = 'ok';
        return $result;
    }
}
