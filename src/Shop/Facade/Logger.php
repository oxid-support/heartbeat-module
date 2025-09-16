<?php

declare(strict_types=1);

namespace OxidSupport\RequestLogger\Shop\Facade;

use OxidEsales\Eshop\Core\Registry;
use Psr\Log\LoggerInterface;

class Logger
{
    public function get(): LoggerInterface
    {
        return Registry::getLogger();
    }

    public function getLogsDir(): string
    {
        return (new Config())->get()->getLogsDir();
    }
}
