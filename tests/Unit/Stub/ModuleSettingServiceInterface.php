<?php

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Internal\Framework\Module\Facade;

/**
 * Stub interface for unit testing when OXID dependencies are not available.
 */
interface ModuleSettingServiceInterface
{
    public function getBoolean(string $name, string $moduleId): bool;
    public function getInteger(string $name, string $moduleId): int;
    public function getString(string $name, string $moduleId): string;
    public function getCollection(string $name, string $moduleId): array;
    public function saveBoolean(string $name, bool $value, string $moduleId): void;
    public function saveInteger(string $name, int $value, string $moduleId): void;
    public function saveString(string $name, string $value, string $moduleId): void;
    public function saveCollection(string $name, array $value, string $moduleId): void;
}
