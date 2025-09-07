<?php
declare(strict_types=1);

namespace OxidSupport\Logger\Logger;

use OxidEsales\Eshop\Core\Registry;

final class RequestContext
{
    /** @return array<string,mixed> */
    public static function build(): array
    {
        $config  = Registry::getConfig();
        $session = Registry::getSession();
        $user    = $session?->getUser();

        $scheme = $_SERVER['REQUEST_SCHEME'] ?? (($_SERVER['HTTPS'] ?? '') === 'on' ? 'https' : 'http');
        $host   = $_SERVER['HTTP_HOST'] ?? '';
        $uri    = $_SERVER['REQUEST_URI'] ?? '/';

        return [
            'ts'        => date('c'),
            'shopId'    => (int) $config->getShopId(),
            'shopUrl'   => (string) $config->getShopUrl(),
            'requestId' => self::requestId(),
            'sessionId' => $session === false ? 'no session' : $session->getId(),
            'userId'    => $user === false ? 'no user' : $user->getId(),
            'userLogin' => $user?->oxuser__oxusername->rawValue ?? null,
            'ip'        => $_SERVER['REMOTE_ADDR'] ?? null,
            'method'    => $_SERVER['REQUEST_METHOD'] ?? null,
            'uri'       => "{$scheme}://{$host}{$uri}",
            'lang'      => Registry::getLang()->getLanguageAbbr(),
            'edition'   => (defined('OXID_ENTERPRISE_EDITION') ? 'EE' : (defined('OXID_PROFESSIONAL_EDITION') ? 'PE' : 'CE')),
            'php'       => PHP_VERSION,
            'oxid'      => '',
            'ua'        => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'referer'   => $_SERVER['HTTP_REFERER'] ?? null,
            'cl'        => (string) (Registry::getRequest()->getRequestParameter('cl') ?? ''),    // nach SEO-Auflösung
            'fnc'       => (string) (Registry::getRequest()->getRequestParameter('fnc') ?? 'render'),
        ];
    }

    public static function requestId(): string
    {
        static $id = null;
        if ($id === null) {
            $id = bin2hex(random_bytes(8));
        }
        return $id;
    }
}
