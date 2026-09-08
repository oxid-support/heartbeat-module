<?php

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Shop\Extend\Core;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\ShopControl as CoreShopControl;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidSupport\Heartbeat\Component\RequestLogger\Infrastructure\Logger\Security\SensitiveDataRedactorInterface;
// phpcs:ignore Generic.Files.LineLength.TooLong
use OxidSupport\Heartbeat\Component\RequestLogger\Infrastructure\Logger\ShopRequestRecorder\ShopRequestRecorderInterface;
use OxidSupport\Heartbeat\Component\RequestLogger\Infrastructure\Logger\SymbolTracker;
use OxidSupport\Heartbeat\Shop\Facade\ModuleSettingFacadeInterface;
use OxidSupport\Heartbeat\Shop\Facade\ShopFacadeInterface;
use Psr\Container\ContainerInterface;

class ShopControl extends CoreShopControl
{
    /**
     * @param array<mixed>|null $parameters
     * @param array<mixed>|null $viewsChain
     */
    public function start($controllerKey = null, $function = null, $parameters = null, $viewsChain = null): void
    {
        $recorder = $this->resolveRecorder();

        if ($recorder === null) {
            parent::start($controllerKey, $function, $parameters, $viewsChain);
            return;
        }

        try {
            $this->logStart($recorder);
        } catch (\Throwable $e) {
            $this->reportSkippedLogging($e->getMessage(), $e);
        }

        SymbolTracker::enable();
        $calculateDurationTimestampStart = microtime(true);

        try {
            parent::start($controllerKey, $function, $parameters, $viewsChain);
        } finally {
            $calculateDurationTimestampStop = microtime(true);

            try {
                $this->logSymbols(
                    $recorder,
                    SymbolTracker::report()
                );

                $this->logFinish(
                    $recorder,
                    $calculateDurationTimestampStart,
                    $calculateDurationTimestampStop
                );
            } catch (\Throwable $e) {
                $this->reportSkippedLogging($e->getMessage(), $e);
            }
        }
    }

    /**
     * Returns the recorder when this request is to be logged, and null when the request
     * logger has to stay out of the way.
     *
     * This class extension sits in the chain as soon as the module is active, which says
     * nothing about the container knowing the module's services. A request that boots
     * while the module imports are missing from generated_services.yaml caches a container
     * without them, and that cache outlives the request, so taking the services
     * unconditionally turned every following request into "You have requested a
     * non-existent service" and the shop answered with the maintenance page until the next
     * module configuration change. See OXS-3379.
     */
    private function resolveRecorder(): ?ShopRequestRecorderInterface
    {
        $container = ContainerFactory::getInstance()->getContainer();

        if (!$this->hasModuleServices($container)) {
            // Drop the incomplete container instead of leaving the shop in that state:
            // the next request then compiles a complete one. See OXS-3379.
            ContainerFactory::resetContainer();
            $this->reportSkippedLogging('the container carries no heartbeat services, its cache was dropped');

            return null;
        }

        try {
            /** @var ModuleSettingFacadeInterface $settingsFacade */
            $settingsFacade = $container->get(ModuleSettingFacadeInterface::class);

            if (!$settingsFacade->isRequestLoggerComponentActive()) {
                return null;
            }

            /** @var ShopFacadeInterface $shopFacade */
            $shopFacade = $container->get(ShopFacadeInterface::class);

            $isAdmin = $shopFacade->isAdmin();
            $shouldLog = ($isAdmin && $settingsFacade->isLogAdminEnabled())
                || (!$isAdmin && $settingsFacade->isLogFrontendEnabled());

            if (!$shouldLog) {
                return null;
            }

            /** @var ShopRequestRecorderInterface $recorder */
            $recorder = $container->get(ShopRequestRecorderInterface::class);

            return $recorder;
        } catch (\Throwable $e) {
            $this->reportSkippedLogging($e->getMessage(), $e);

            return null;
        }
    }

    /**
     * The services the logging path needs before it may start, pinned in one place so a
     * new dependency in that path cannot slip past the guard unnoticed. See OXS-3379.
     */
    private function hasModuleServices(ContainerInterface $container): bool
    {
        return $container->has(ShopFacadeInterface::class)
            && $container->has(ModuleSettingFacadeInterface::class)
            && $container->has(SensitiveDataRedactorInterface::class)
            && $container->has(ShopRequestRecorderInterface::class);
    }

    /**
     * A defect in the logging path costs log entries, an exception out of it costs the
     * shop. The request logger therefore reports and steps aside. See OXS-3379.
     */
    private function reportSkippedLogging(string $reason, ?\Throwable $e = null): void
    {
        try {
            Registry::getLogger()->error(
                'Heartbeat: request logging skipped: ' . $reason,
                $e === null ? [] : [$e]
            );
        } catch (\Throwable $reportingFailure) {
            // Nothing left to report to, and the request has to survive either way.
        }
    }

    private function logStart(
        ShopRequestRecorderInterface $recorder
    ): void {

        /** @var ShopFacadeInterface $facade */
        $facade = ContainerFactory::getInstance()->getContainer()->get(ShopFacadeInterface::class);
        /** @var SensitiveDataRedactorInterface $redactor */
        $redactor = ContainerFactory::getInstance()->getContainer()->get(SensitiveDataRedactorInterface::class);
        /** @var ModuleSettingFacadeInterface $settingsFacade */
        $settingsFacade = ContainerFactory::getInstance()->getContainer()->get(ModuleSettingFacadeInterface::class);

        $referer   = $_SERVER['HTTP_REFERER'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

        $get  = $redactor->redact($_GET);
        $post = $redactor->redact($_POST);

        $redactAll = $settingsFacade->isRedactAllValuesEnabled();

        $scheme = $_SERVER['REQUEST_SCHEME'] ?? (($_SERVER['HTTPS'] ?? '') === 'on' ? 'https' : 'http');
        $host   = $_SERVER['HTTP_HOST'] ?? '';
        $uri    = $_SERVER['REQUEST_URI'] ?? '/';

        // Redact query parameters in referer and URI in BOTH modes. In blocklist
        // mode only blocklisted keys are redacted; this closes the leak where a
        // blocklisted value (e.g. ?token=SECRET) was still logged raw in the URI.
        $blocklistLower = array_map('strtolower', $settingsFacade->getRedactItems());
        $uri = $this->redactUrlQueryParams(
            sprintf("%s://%s%s", $scheme, $host, $uri),
            $redactAll,
            $blocklistLower
        );
        $referer = $this->redactUrlQueryParams($referer, $redactAll, $blocklistLower);

        $recorder->logStart([

            'version'    => $facade->getShopVersion(),
            'edition'    => $facade->getShopEdition(),
            'shopId'     => $facade->getShopId(),
            'shopUrl'    => $facade->getShopUrl(),

            'referer'    => $referer,
            'uri'        => $uri,
            'method'     => $_SERVER['REQUEST_METHOD'] ?? null,
            'get'        => $get,
            'post'       => $post,
            'userAgent'  => $redactAll ? '[redacted]' : $userAgent,
            'lang'       => $facade->getLanguageAbbreviation(),

            'sessionId'  => $redactAll ? '[redacted]' : $this->pseudonymizeSessionId($facade->getSessionId()),
            'userId'     => $redactAll ? '[redacted]' : $facade->getUserId(),
            'username'   => $redactAll ? '[redacted]' : $facade->getUsername(),
            'ip'         => $redactAll ? '[redacted]' : ($_SERVER['REMOTE_ADDR'] ?? null),

            'php'        => PHP_VERSION,
        ]);
    }

    /** @param array<string, mixed> $symbols */
    private function logSymbols(ShopRequestRecorderInterface $recorder, array $symbols): void
    {
        $recorder->logSymbols($symbols);
    }

    private function logFinish(
        ShopRequestRecorderInterface $recorder,
        float $calculateDurationStartTimestamp,
        float $calculateDurationStopTimestamp
    ): void {
        $duration = (int) round(
            ($calculateDurationStopTimestamp - $calculateDurationStartTimestamp) * 1000
        );

        $recorder->logFinish([
            'durationMs' => $duration,
            'memoryMb'   => round(memory_get_peak_usage(true) / 1048576, 1),
        ]);
    }

    /**
     * Return a stable pseudonym of the session id instead of the raw value.
     * The raw session id is a live authentication token; logging it enables
     * session hijacking if logs are readable. The pseudonym keeps request
     * correlation intact for support without exposing the token.
     */
    private function pseudonymizeSessionId(?string $sessionId): ?string
    {
        if ($sessionId === null || $sessionId === '') {
            return $sessionId;
        }

        return 'sha256:' . substr(hash('sha256', $sessionId), 0, 16);
    }

    /**
     * redact-all mode: redact every query value except the harmless routing
     * params. blocklist mode: redact only keys on the blocklist. Either way the
     * query string of uri/referer is covered, so a blocklisted value can no
     * longer leak through the raw URL.
     *
     * @param string[] $excludeFromRedaction
     * @param string[] $blocklistLower lowercase blocklist entries
     */
    private function shouldRedactQueryKey(
        string $key,
        bool $redactAll,
        array $excludeFromRedaction,
        array $blocklistLower
    ): bool {
        if ($redactAll) {
            return !in_array($key, $excludeFromRedaction, true);
        }

        return in_array(strtolower($key), $blocklistLower, true);
    }

    /**
     * @param string[] $blocklistLower lowercase blocklist entries (blocklist mode only)
     */
    private function redactUrlQueryParams(?string $url, bool $redactAll, array $blocklistLower): ?string
    {
        if ($url === null) {
            return null;
        }

        $parsedUrl = parse_url($url);
        if ($parsedUrl === false || !isset($parsedUrl['query'])) {
            return $url;
        }

        parse_str($parsedUrl['query'], $queryParams);

        // Parameters that should not be redacted (controller and function names)
        $excludeFromRedaction = ['cl', 'fnc', 'item'];

        // Build query string manually to avoid double URL-encoding of [redacted]
        $queryParts = [];
        foreach ($queryParams as $key => $value) {
            $encodedKey = urlencode((string) $key);

            if ($this->shouldRedactQueryKey((string) $key, $redactAll, $excludeFromRedaction, $blocklistLower)) {
                // Use literal [redacted] without URL encoding
                $queryParts[] = $encodedKey . '=[redacted]';
            } else {
                $encodedValue = urlencode(is_array($value) ? '' : (string) $value);
                $queryParts[] = $encodedKey . '=' . $encodedValue;
            }
        }

        $redactedQuery = implode('&', $queryParts);

        $result = '';
        if (isset($parsedUrl['scheme'])) {
            $result .= $parsedUrl['scheme'] . '://';
        }
        if (isset($parsedUrl['host'])) {
            $result .= $parsedUrl['host'];
        }
        if (isset($parsedUrl['port'])) {
            $result .= ':' . $parsedUrl['port'];
        }
        if (isset($parsedUrl['path'])) {
            $result .= $parsedUrl['path'];
        }
        if ($redactedQuery !== '') {
            $result .= '?' . $redactedQuery;
        }
        if (isset($parsedUrl['fragment'])) {
            $result .= '#' . $parsedUrl['fragment'];
        }

        return $result;
    }
}
