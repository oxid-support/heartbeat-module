<?php
declare(strict_types=1);


namespace OxidSupport\Logger\Logger;

class ShopRequestLoggerFactory
{
    public static function create(ShopRequestLoggerInterface $shopLogger): ShopRequestLoggerInterface
    {
        $shopLogger->create();
        return $shopLogger;
    }
}
