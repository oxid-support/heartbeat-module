<?php
declare(strict_types=1);

namespace OxidSupport\RequestLogger\Logger\CorrelationId;

interface CorrelationIdProviderInterface
{
    public function provide(): string;
}
