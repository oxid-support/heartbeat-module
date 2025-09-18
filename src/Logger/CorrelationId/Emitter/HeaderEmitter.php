<?php

declare(strict_types=1);

namespace OxidSupport\RequestLogger\Logger\CorrelationId\Emitter;

class HeaderEmitter implements EmitterInterface
{
    public function __construct(
        private string $headerName,
    ){}

    public function emit(string $id): void
    {
        if (headers_sent()) {
            return;
        }

        header(strtoupper($this->headerName) . ': ' . $id);
    }
}
