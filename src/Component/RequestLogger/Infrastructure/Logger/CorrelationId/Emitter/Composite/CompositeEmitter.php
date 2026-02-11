<?php

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\RequestLogger\Infrastructure\Logger\CorrelationId\Emitter\Composite;

use OxidSupport\Heartbeat\Component\RequestLogger\Infrastructure\Logger\CorrelationId\Emitter\EmitterInterface;

class CompositeEmitter implements EmitterInterface
{
    /** @var EmitterInterface[] */
    private iterable $emitters;

    /** @param iterable<EmitterInterface> $emitters */
    public function __construct(iterable $emitters)
    {
        $this->emitters = $emitters;
    }

    public function emit(string $id): void
    {
        foreach ($this->emitters as $emitter) {
            $emitter->emit($id);
        }
    }
}
