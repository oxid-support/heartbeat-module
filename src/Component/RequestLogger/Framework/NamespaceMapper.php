<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Component\RequestLogger\Framework;

use OxidEsales\GraphQL\Base\Framework\NamespaceMapperInterface;

final class NamespaceMapper implements NamespaceMapperInterface
{
    public function getControllerNamespaceMapping(): array
    {
        return [
            'OxidSupport\\Heartbeat\\Component\\RequestLogger\\Controller\\GraphQL' => __DIR__ . '/../Controller/GraphQL/',
        ];
    }

    public function getTypeNamespaceMapping(): array
    {
        return [
            'OxidSupport\\Heartbeat\\Component\\RequestLogger\\DataType' => __DIR__ . '/../DataType/',
        ];
    }
}
