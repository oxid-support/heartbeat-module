<?php

declare(strict_types=1);

namespace OxidSupport\RequestLogger\Logger\CorrelationId;

use OxidSupport\RequestLogger\Logger\CorrelationId\Emitter\EmitterInterface;
use OxidSupport\RequestLogger\Logger\CorrelationId\Resolver\ResolverInterface;

final class CorrelationIdProvider implements CorrelationIdProviderInterface
{
    public function __construct(
        private EmitterInterface $emitter,
        private CorrelationIdGeneratorInterface $generator,
        private ResolverInterface $resolver,
    ) {}

    public function provide(): string
    {
        $id = $this->resolver->resolve() ?? $this->generator->generate();
        $this->emitter->emit($id);

        return $id;
    }
}
