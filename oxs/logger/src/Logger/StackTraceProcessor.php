<?php
declare(strict_types=1);

namespace OxidSupport\Logger\Logger;

final class StackTraceProcessor
{
    public function __construct(
        private int $maxDepth = 12,
        private bool $includeArgs = false,
        private array $includeEvents = [
            'user.view',
            'controller.render'
        ],
    ) {}

    /** @param array<string,mixed> $record */
    public function __invoke(array $record): array
    {
        $msg = (string)($record['message'] ?? '');

        // Nur für Events mit echtem Mehrwert (z. B. user.view)
        if (!in_array($msg, $this->includeEvents, true)) {
            return $record;
        }

        $flags = $this->includeArgs ? 0 : DEBUG_BACKTRACE_IGNORE_ARGS;
        $trace = debug_backtrace($flags, $this->maxDepth);

        $norm = [];
        foreach ($trace as $f) {
            $norm[] = [
                'file'  => $f['file'] ?? null,
                'line'  => $f['line'] ?? null,
                'class' => $f['class'] ?? null,
                'type'  => $f['type'] ?? null,
                'func'  => $f['function'] ?? null,
            ];
        }

        $record['extra']['trace'] = $norm;
        return $record;
    }
}
