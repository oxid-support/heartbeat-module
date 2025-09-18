<?php

declare(strict_types=1);


namespace OxidSupport\RequestLogger\Logger\Security;

class SensitiveDataRedactor
{
    public function sanitize(array $values): array
    {
        $blocklistLower = [
            'lgn_pwd',
            'lgn_pwd2',
        ];

        $out = [];

        foreach ($values as $k => $v) {
            $key = (string) $k;

            if (in_array(strtolower($key), $blocklistLower, true)) {
                $out[$key] = '[redacted]';
                continue;
            }

            // Arrays/Objekte vollständig als JSON (keine Limits, nichts abschneiden) //@todo
            if (is_array($v) || is_object($v)) {
                $json = json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                $out[$key] = $json !== false ? $json : '[unserializable]';
                continue;
            }

            // Strings/Skalare/NULL: unverändert
            $out[$key] = $v;
        }

        return $out;
    }
}
