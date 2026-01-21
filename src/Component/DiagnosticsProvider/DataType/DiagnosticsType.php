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
 * Main diagnostics data type containing all diagnostic information
 */
#[Type]
final class DiagnosticsType
{
    /**
     * @param DiagnosticsSectionType[] $sections
     */
    public function __construct(
        private readonly array $sections,
        private readonly string $phpDecoder,
    ) {
    }

    /**
     * @return DiagnosticsSectionType[]
     */
    #[Field]
    public function getSections(): array
    {
        return $this->sections;
    }

    #[Field]
    public function getPhpDecoder(): string
    {
        return $this->phpDecoder;
    }

    /**
     * Convert the diagnostics array from getDiagnostics() to DiagnosticsType
     */
    public static function fromDiagnosticsArray(array $diagnostics): self
    {
        $sections = [];

        // Extract PHP decoder separately as it's a string, not an array
        $phpDecoder = $diagnostics['sPhpDecoder'] ?? '';

        // Convert each array section to DiagnosticsSectionType
        $arrayKeys = ['aShopDetails', 'aModuleList', 'aInfo', 'aCollations', 'aPhpConfigparams', 'aServerInfo'];

        foreach ($arrayKeys as $key) {
            if (isset($diagnostics[$key]) && is_array($diagnostics[$key])) {
                // Convert key to readable name
                $sectionName = match($key) {
                    'aShopDetails' => 'Shop Details',
                    'aModuleList' => 'Module List',
                    'aInfo' => 'System Information',
                    'aCollations' => 'Database Collations',
                    'aPhpConfigparams' => 'PHP Configuration',
                    'aServerInfo' => 'Server Information',
                    default => $key,
                };

                $sections[] = DiagnosticsSectionType::fromArray($sectionName, $diagnostics[$key]);
            }
        }

        return new self($sections, $phpDecoder);
    }
}
