<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\RequestLogger\DataType;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type(name="RequestLoggerSettingType")
 */
final class SettingType
{
    private string $name;
    private string $type;
    private bool $supported;

    public function __construct(string $name, string $type, bool $supported = true)
    {
        $this->name = $name;
        $this->type = $type;
        $this->supported = $supported;
    }

    /**
     * @Field
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @Field
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @Field
     */
    public function isSupported(): bool
    {
        return $this->supported;
    }
}
