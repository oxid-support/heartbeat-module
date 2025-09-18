<?php

declare(strict_types=1);

namespace OxidSupport\RequestLogger\Logger\CorrelationId\Emitter;

interface EmitterInterface
{
    public function emit(string $id): void;
}
