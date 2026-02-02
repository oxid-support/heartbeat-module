<?php

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\RequestLogger\Infrastructure\Logger\CorrelationId;

interface CorrelationIdProviderInterface
{
    public function provide(): string;
}
