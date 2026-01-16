<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\DiagnosticsProvider\DataType;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * Represents a key-value pair for diagnostics data
 */
#[Type]
final class KeyValueType
{
    public function __construct(
        private readonly string $key,
        private readonly string $value,
    ) {
    }

    #[Field]
    public function getKey(): string
    {
        return $this->key;
    }

    #[Field]
    public function getValue(): string
    {
        return $this->value;
    }
}
