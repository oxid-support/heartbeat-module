<?php

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\RequestLogger\Infrastructure\Logger\CorrelationId\Resolver\Composite;

use OxidSupport\LoggingFramework\Component\RequestLogger\Infrastructure\Logger\CorrelationId\Resolver\ResolverInterface;

class CompositeResolver implements ResolverInterface
{
    /** @var ResolverInterface[] */
    private iterable $resolvers;

    public function __construct(iterable $resolvers)
    {
        $this->resolvers = $resolvers;
    }

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
