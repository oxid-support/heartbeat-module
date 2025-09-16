<?php

declare(strict_types=1);

namespace OxidSupport\RequestLogger\Shop\Facade;

use OxidEsales\Eshop\Core\Registry;

class Config
{
    public function get(): \OxidEsales\Eshop\Core\Config
    {
        return Registry::getConfig();
    }
}
