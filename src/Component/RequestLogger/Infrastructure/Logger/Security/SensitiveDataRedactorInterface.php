<?php

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\RequestLogger\Infrastructure\Logger\Security;

interface SensitiveDataRedactorInterface
{
    /**
     * @param array<string, mixed> $values
     * @return array<string, mixed>
     */
    public function redact(array $values): array;
}
