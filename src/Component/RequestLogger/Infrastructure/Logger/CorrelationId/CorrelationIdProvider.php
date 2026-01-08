<?php

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\RequestLogger\Infrastructure\Logger\CorrelationId;

use OxidSupport\LoggingFramework\Component\RequestLogger\Infrastructure\Logger\CorrelationId\Emitter\EmitterInterface;
use OxidSupport\LoggingFramework\Component\RequestLogger\Infrastructure\Logger\CorrelationId\Resolver\ResolverInterface;

final class CorrelationIdProvider implements CorrelationIdProviderInterface
{
    private EmitterInterface $emitter;
    private CorrelationIdGeneratorInterface $generator;
    private ResolverInterface $resolver;

    public function __construct(
        EmitterInterface $emitter,
        CorrelationIdGeneratorInterface $generator,
        ResolverInterface $resolver
    ) {
        $this->emitter = $emitter;
        $this->generator = $generator;
        $this->resolver = $resolver;
    }

    public function provide(): string
    {
        $id = $this->resolver->resolve() ?? $this->generator->generate();
        $this->emitter->emit($id);

        return $id;
    }
}
