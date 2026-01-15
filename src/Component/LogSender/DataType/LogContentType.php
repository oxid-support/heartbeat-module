<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\LogSender\DataType;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

#[Type]
final class LogContentType
{
    public function __construct(
        private readonly string $sourceId,
        private readonly string $sourceName,
        private readonly string $path,
        private readonly string $content,
        private readonly int $size,
        private readonly int $modified,
        private readonly bool $truncated,
    ) {
    }

    #[Field]
    public function getSourceId(): string
    {
        return $this->sourceId;
    }

    #[Field]
    public function getSourceName(): string
    {
        return $this->sourceName;
    }

    #[Field]
    public function getPath(): string
    {
        return $this->path;
    }

    #[Field]
    public function getContent(): string
    {
        return $this->content;
    }

    #[Field]
    public function getSize(): int
    {
        return $this->size;
    }

    #[Field]
    public function getModified(): int
    {
        return $this->modified;
    }

    #[Field]
    public function isTruncated(): bool
    {
        return $this->truncated;
    }
}
