<?php
declare(strict_types=1);

namespace OxidSupport\RequestLogger\Logger\CorrelationId\Resolver;

interface ResolverInterface
{
    public function resolve(): ?string;
}
