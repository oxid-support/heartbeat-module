<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\LogSender\DataType;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type
 */
final class LogSourceType
{
    private string $id;
    private string $name;
    private string $description;
    private string $origin;
    private bool $available;

    /** @var LogPath[] */
    private array $paths;

    /**
     * @param LogPath[] $paths
     */
    public function __construct(
        string $id,
        string $name,
        string $description,
        string $origin,
        bool $available,
        array $paths
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->origin = $origin;
        $this->available = $available;
        $this->paths = $paths;
    }

    /**
     * @Field
     */
    public function getId(): string
    {
        return $this->id;
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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @Field
     */
    public function getOrigin(): string
    {
        return $this->origin;
    }

    /**
     * @Field
     */
    public function isAvailable(): bool
    {
        return $this->available;
    }

    /**
     * @return LogPathInfoType[]
     *
     * @Field
     */
    public function getPaths(): array
    {
        return array_map(
            fn(LogPath $path) => new LogPathInfoType(
                $path->path,
                $path->type->value,
                $path->name,
                $path->description,
                $path->exists(),
                $path->isReadable()
            ),
            $this->paths
        );
    }

    public static function fromLogSource(LogSource $source): self
    {
        return new self(
            $source->id,
            $source->name,
            $source->description,
            $source->origin,
            $source->available,
            $source->paths
        );
    }
}
