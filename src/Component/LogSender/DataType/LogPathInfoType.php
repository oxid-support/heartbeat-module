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
final class LogPathInfoType
{
    public function __construct(
        private readonly string $path,
        private readonly string $type,
        private readonly string $name,
        private readonly string $description,
        private readonly bool $exists,
        private readonly bool $readable,
    ) {
    }

    #[Field]
    public function getPath(): string
    {
        return $this->path;
    }

    #[Field]
    public function getType(): string
    {
        return $this->type;
    }

    #[Field]
    public function getName(): string
    {
        return $this->name;
    }

    #[Field]
    public function getDescription(): string
    {
        return $this->description;
    }

    #[Field]
    public function isExists(): bool
    {
        return $this->exists;
    }

    #[Field]
    public function isReadable(): bool
    {
        return $this->readable;
    }
}
