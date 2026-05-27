<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\LogSender\DataType;

/**
 * Value object representing the type of a log path (PHP 8.0 compatible replacement for enum).
 * Used to explicitly distinguish between files and directories.
 */
final class LogPathType
{
    public const FILE_VALUE = 'file';
    public const DIRECTORY_VALUE = 'directory';

    public string $value;

    /** @var self|null */
    private static ?self $fileInstance = null;
    /** @var self|null */
    private static ?self $directoryInstance = null;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function FILE(): self
    {
        if (self::$fileInstance === null) {
            self::$fileInstance = new self(self::FILE_VALUE);
        }
        return self::$fileInstance;
    }

    public static function DIRECTORY(): self
    {
        if (self::$directoryInstance === null) {
            self::$directoryInstance = new self(self::DIRECTORY_VALUE);
        }
        return self::$directoryInstance;
    }

    public static function tryFrom(string $value): ?self
    {
        switch ($value) {
            case self::FILE_VALUE:
                return self::FILE();
            case self::DIRECTORY_VALUE:
                return self::DIRECTORY();
            default:
                return null;
        }
    }

    public function getLabel(): string
    {
        switch ($this->value) {
            case self::FILE_VALUE:
                return 'File';
            case self::DIRECTORY_VALUE:
                return 'Directory';
            default:
                return '';
        }
    }

    public function getLabelDe(): string
    {
        switch ($this->value) {
            case self::FILE_VALUE:
                return 'Datei';
            case self::DIRECTORY_VALUE:
                return 'Verzeichnis';
            default:
                return '';
        }
    }
}
