<?php

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\RequestLogger\Infrastructure\Logger\CorrelationId\Emitter;

interface EmitterInterface
{
    public function emit(string $id): void;
}
