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
 *
 * @Type
 */
final class DiagnosticsType
{
    /** @var DiagnosticsSectionType[] */
    private array $sections;

    private string $phpDecoder;

    /**
     * @param DiagnosticsSectionType[] $sections
     */
    public function __construct(array $sections, string $phpDecoder)
    {
        $this->sections = $sections;
        $this->phpDecoder = $phpDecoder;
    }

    /**
     * @return DiagnosticsSectionType[]
     *
     * @Field
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    /**
     * @Field
     */
    public function getPhpDecoder(): string
    {
        return $this->phpDecoder;
    }

    /**
     * Convert the diagnostics array from getDiagnostics() to DiagnosticsType
     *
     * @param array<string, mixed> $diagnostics
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
                switch ($key) {
                    case 'aShopDetails':
                        $sectionName = 'Shop Details';
                        break;
                    case 'aModuleList':
                        $sectionName = 'Module List';
                        break;
                    case 'aInfo':
                        $sectionName = 'System Information';
                        break;
                    case 'aCollations':
                        $sectionName = 'Database Collations';
                        break;
                    case 'aPhpConfigparams':
                        $sectionName = 'PHP Configuration';
                        break;
                    case 'aServerInfo':
                        $sectionName = 'Server Information';
                        break;
                    default:
                        $sectionName = $key;
                        break;
                }

                $sections[] = DiagnosticsSectionType::fromArray($sectionName, $diagnostics[$key]);
            }
        }

        return new self($sections, $phpDecoder);
    }
}
