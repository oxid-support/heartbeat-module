<?php

declare(strict_types=1);

namespace OxidSupport\RequestLogger\Logger\CorrelationId\Resolver\Composite;

use OxidSupport\RequestLogger\Logger\CorrelationId\Resolver\ResolverInterface;

class CompositeResolver implements ResolverInterface
{
    /** ResolverEmitter[] $emitters */
    public function __construct(
        private iterable $resolvers,
    ) {}

    public function resolve(): ?string
    {
        foreach ($this->resolvers as $resolver) {
            $id = $resolver->resolve();
            if ($id !== null) {
                return $id;
            }
        }
        return null;
    }
}
