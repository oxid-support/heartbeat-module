<?php

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\RequestLogger\Infrastructure\Logger\CorrelationId\Emitter\Decorator;

use OxidSupport\LoggingFramework\Component\RequestLogger\Infrastructure\Logger\CorrelationId\Emitter\Composite\CompositeEmitter;
use OxidSupport\LoggingFramework\Component\RequestLogger\Infrastructure\Logger\CorrelationId\Emitter\EmitterInterface;

class OnceEmitterDecorator implements EmitterInterface
{
    public bool $emitted = false;
    private CompositeEmitter $emitter;

    public function __construct(CompositeEmitter $emitter)
    {
        $this->emitter = $emitter;
    }

    public function emit(string $id): void
    {
        if (!$this->emitted) {
            $this->emitter->emit($id);
            $this->emitted = true;
        }
    }
}
