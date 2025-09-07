<?php
declare(strict_types=1);

namespace OxidSupport\Logger\Logger;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use OxidEsales\Eshop\Core\Registry;

final class ShopLogger
{
    private static ?LoggerInterface $instance = null;

    public static function get(): LoggerInterface
    {
        if (self::$instance instanceof LoggerInterface) {
            return self::$instance;
        }

        $logFile = self::logFilePath();
        if (!is_dir($logFile)) {
            @mkdir($logFile, 0775, true);
        }

        $handler = new StreamHandler($logFile . self::logFileName(), Logger::INFO, true, 0664);
        $handler->setFormatter(new JsonFormatter());

        $logger = new Logger('oxslogger');
        $logger->pushHandler($handler);

        // Kontext-Processor: immer Request-Kontext anhängen
        //$logger->pushProcessor(function (array $record): array {
        //    $record['extra']['context'] = RequestContext::build();
        //    return $record;
        //});

        $logger->pushProcessor(new RequestContextProcessor());

        // Stacktrace NUR für Aktions-Events
        $logger->pushProcessor(new StackTraceProcessor(
            maxDepth: (int)($_ENV['OXSL_STACK_DEPTH'] ?? 12),
            includeArgs: false
        ));

        self::$instance = $logger;
        return self::$instance;
    }

    private static function logFilePath(): string
    {
        return
            OX_BASE_PATH .
            'log' . DIRECTORY_SEPARATOR .
            'oxs-logger' . DIRECTORY_SEPARATOR;
    }

    private static function logFileName(): string
    {
        //return RequestContext::requestId() . '.log';
        return 'oxs-request.json';
    }
}
