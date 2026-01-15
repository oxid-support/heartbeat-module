<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\LogSender\Framework;

use OxidEsales\GraphQL\Base\Framework\NamespaceMapperInterface;

final class NamespaceMapper implements NamespaceMapperInterface
{
    public function getControllerNamespaceMapping(): array
    {
        return [
            'OxidSupport\\LoggingFramework\\Component\\LogSender\\Controller\\GraphQL' => __DIR__ . '/../Controller/GraphQL/',
        ];
    }

    public function getTypeNamespaceMapping(): array
    {
        return [
            'OxidSupport\\LoggingFramework\\Component\\LogSender\\DataType' => __DIR__ . '/../DataType/',
        ];
    }
}
