<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSupport\LoggingFramework\Component\RequestLoggerRemote\Framework;

use OxidEsales\GraphQL\Base\Framework\NamespaceMapperInterface;

final class NamespaceMapper implements NamespaceMapperInterface
{
    public function getControllerNamespaceMapping(): array
    {
        return [
            // Only map the GraphQL Controller namespace (not Admin subdirectory)
            // GraphQL will scan the directory for classes with #[Query] or #[Mutation] attributes
            'OxidSupport\\RequestLogger\\Component\\Remote\\Controller\\GraphQL' => __DIR__ . '/../Controller/GraphQL/',
        ];
    }

    public function getTypeNamespaceMapping(): array
    {
        return [
            'OxidSupport\\RequestLogger\\Component\\Remote\\DataType' => __DIR__ . '/../DataType/',
        ];
    }
}
