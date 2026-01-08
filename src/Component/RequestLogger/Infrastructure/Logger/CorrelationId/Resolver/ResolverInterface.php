<?php
declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\RequestLogger\Infrastructure\Logger\CorrelationId\Resolver;

interface ResolverInterface
{
    public function resolve(): ?string;
}
