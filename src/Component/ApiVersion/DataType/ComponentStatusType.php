<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\ApiVersion\DataType;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * GraphQL type for component activation status.
 * Allows the dashboard to check which components are enabled before calling their operations.
 */
#[Type]
final class ComponentStatusType
{
    public function __construct(
        private readonly string $name,
        private readonly bool $active,
    ) {
    }

    #[Field]
    public function getName(): string
    {
        return $this->name;
    }

    #[Field]
    public function isActive(): bool
    {
        return $this->active;
    }
}
