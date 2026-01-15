<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\LogSender\DataType;

/**
 * Value object representing an aggregated log source.
 * A source can contain multiple log paths and tracks availability.
 */
final class LogSource
{
    public const ORIGIN_STATIC = 'static';
    public const ORIGIN_PROVIDER = 'provider';

    /**
     * @param string $id Unique identifier for this source
     * @param string $name Display name
     * @param string $description Description of the log source
     * @param string $origin Origin type: 'static' or 'provider'
     * @param string|null $providerId Provider ID if origin is 'provider'
     * @param LogPath[] $paths The log paths belonging to this source
     * @param bool $available Whether the source is available/reachable
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $description,
        public readonly string $origin,
        public readonly ?string $providerId,
        public readonly array $paths,
        public readonly bool $available,
    ) {
    }

    public function isStatic(): bool
    {
        return $this->origin === self::ORIGIN_STATIC;
    }

    public function isFromProvider(): bool
    {
        return $this->origin === self::ORIGIN_PROVIDER;
    }

    /**
     * Returns the first path or null if no paths are defined.
     */
    public function getFirstPath(): ?LogPath
    {
        return $this->paths[0] ?? null;
    }

    /**
     * Returns the path at the given index or null.
     */
    public function getPathAt(int $index): ?LogPath
    {
        return $this->paths[$index] ?? null;
    }

    /**
     * Returns the number of paths in this source.
     */
    public function getPathCount(): int
    {
        return count($this->paths);
    }

    /**
     * Converts the LogSource to an array representation.
     *
     * @return array{id: string, name: string, description: string, origin: string, providerId: string|null, paths: array, available: bool}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'origin' => $this->origin,
            'providerId' => $this->providerId,
            'paths' => array_map(fn(LogPath $path) => $path->toArray(), $this->paths),
            'available' => $this->available,
        ];
    }
}
