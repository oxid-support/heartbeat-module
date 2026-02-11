<?php

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\RequestLogger\Infrastructure\Logger\ShopRequestRecorder;

interface ShopRequestRecorderInterface
{
    /** @param array<string, mixed> $record */
    public function logStart(array $record): void;

    /** @param array<string, mixed> $record */
    public function logSymbols(array $record): void;

    /** @param array<string, mixed> $record */
    public function logFinish(array $record): void;
}
