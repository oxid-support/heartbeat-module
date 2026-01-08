<?php

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\RequestLogger\Infrastructure\Logger\CorrelationId;

interface CorrelationIdGeneratorInterface
{
    public function generate(): string;
}
