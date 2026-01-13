<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\LogSender\DataType;

/**
 * Enum representing the type of a log path.
 * Used to explicitly distinguish between files and directories.
 */
enum LogPathType: string
{
    case FILE = 'file';
    case DIRECTORY = 'directory';

    public function getLabel(): string
    {
        return match ($this) {
            self::FILE => 'File',
            self::DIRECTORY => 'Directory',
        };
    }

    public function getLabelDe(): string
    {
        return match ($this) {
            self::FILE => 'Datei',
            self::DIRECTORY => 'Verzeichnis',
        };
    }
}
