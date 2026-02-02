<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\DiagnosticsProvider\DataType;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use OxidEsales\Eshop\Core\Module\Module;

/**
 * Represents a diagnostics section with multiple key-value pairs
 */
#[Type]
final class DiagnosticsSectionType
{
    /**
     * @param KeyValueType[] $items
     */
    public function __construct(
        private readonly string $name,
        private readonly array $items,
    ) {
    }

    #[Field]
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return KeyValueType[]
     */
    #[Field]
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Convert an associative array to DiagnosticsSectionType
     */
    public static function fromArray(string $name, array $data): self
    {
        $items = [];

        foreach ($data as $key => $value) {
            // Convert value to string if it's not already
            if (is_array($value)) {
                $value = json_encode($value);
            } elseif (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            } elseif ($value === null) {
                $value = 'null';
            } elseif (is_a($value, Module::class)) {
                $oldvalue = $value;
                $oldvalue =
                    [
                        $oldvalue->isActive(),
                        $oldvalue->getTitle(),
                        $oldvalue->getInfo('version'),
                        $oldvalue->getInfo('author'),
                    ];
                $value = json_encode($oldvalue);
            } else {
                $value = (string) $value;
            }

            $items[] = new KeyValueType((string) $key, $value);
        }

        return new self($name, $items);
    }
}
