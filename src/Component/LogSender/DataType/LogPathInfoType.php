<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\LogSender\DataType;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

#[Type]
final class LogPathInfoType
{
    public function __construct(
        private string $path,
        private string $type,
        private string $name,
        private string $description,
        private bool $exists,
        private bool $readable,
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
