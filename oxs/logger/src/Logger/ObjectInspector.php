<?php
declare(strict_types=1);

namespace OxidSupport\Logger\Logger;

final class ObjectInspector
{
    /** @return array<string,mixed> */
    public static function fromController(object $ctrl): array
    {
        $out = [];

        // Produkt
        if (method_exists($ctrl, 'getProduct')) {
            $p = $ctrl->getProduct();
            if (is_object($p)) {
                $out['product'] = self::mapEntity($p, ['getId','getTitle','getArtNum','getFTitle']);
            }
        }

        // Kategorie
        if (method_exists($ctrl, 'getActiveCategory')) {
            $c = $ctrl->getActiveCategory();
            if (is_object($c)) {
                $out['category'] = self::mapEntity($c, ['getId','getTitle','getOxtitle']);
            }
        }

        // Hersteller
        if (method_exists($ctrl, 'getManufacturer')) {
            $m = $ctrl->getManufacturer();
            if (is_object($m)) {
                $out['manufacturer'] = self::mapEntity($m, ['getId','getTitle','getOxtitle']);
            }
        }

        // Vendor
        if (method_exists($ctrl, 'getVendor')) {
            $v = $ctrl->getVendor();
            if (is_object($v)) {
                $out['vendor'] = self::mapEntity($v, ['getId','getTitle','getOxtitle']);
            }
        }

        // Warenkorb (nur Größe & Brutto-Summe)
        if (method_exists($ctrl, 'getBasket')) {
            $b = $ctrl->getBasket();
            if (is_object($b)) {
                $out['basket'] = [
                    'items' => method_exists($b,'getItemsCount') ? (int)$b->getItemsCount() : null,
                    'sum'   => (function($b) {
                        if (!method_exists($b,'getPrice')) return null;
                        $price = $b->getPrice();
                        return (is_object($price) && method_exists($price,'getBruttoPrice')) ? (float)$price->getBruttoPrice() : null;
                    })($b),
                ];
            }
        }

        // Controller-Parameter (Getter + GET-Whitelist)
        $params = [];

        // 1) Controller-Getter
        foreach (['getSearchParam','getSortOrder','getSortColumn','getListType','getActCurrency'] as $m) {
            if (method_exists($ctrl, $m)) {
                $val = $ctrl->$m();
                if (is_scalar($val) && $val !== '') {
                    $params[$m] = $val;
                }
            }
        }

        // 2) GET-Whitelist (SEO-/Listen-/Suche-Parameter)
        $whitelist = [
            'searchparam','cnid','mnid','anid','listtype','pgNr','sort','ldtype',
            'searchmanufacturer','searchvendor'
        ];
        foreach ($whitelist as $k) {
            if (isset($_GET[$k]) && is_scalar($_GET[$k])) {
                $params[$k] = (string)$_GET[$k];
            }
        }

        // Immer anlegen (evtl. leeres Array)
        $out['controllerParams'] = $params;

        return $out;
    }

    /** @param array<string,mixed> $vd @return array<string,mixed> */
    public static function fromViewData(array $vd): array
    {
        $out = ['viewObjects' => []];

        foreach ($vd as $k => $v) {
            if (!is_object($v)) continue;

            $cls = get_class($v);
            if (self::endsWith($cls, '\\Model\\Article')) {
                $out['viewObjects'][] = ['type'=>'Article'] + self::mapEntity($v, ['getId','getTitle','getArtNum','getFTitle']);
            } elseif (self::endsWith($cls, '\\Model\\Category')) {
                $out['viewObjects'][] = ['type'=>'Category'] + self::mapEntity($v, ['getId','getTitle','getOxtitle']);
            } elseif (self::endsWith($cls, '\\Model\\Manufacturer')) {
                $out['viewObjects'][] = ['type'=>'Manufacturer'] + self::mapEntity($v, ['getId','getTitle','getOxtitle']);
            } elseif (self::endsWith($cls, '\\Model\\Vendor')) {
                $out['viewObjects'][] = ['type'=>'Vendor'] + self::mapEntity($v, ['getId','getTitle','getOxtitle']);
            } elseif (self::endsWith($cls, '\\Model\\Basket')) {
                $out['viewObjects'][] = ['type'=>'Basket', 'items' => method_exists($v,'getItemsCount') ? (int)$v->getItemsCount() : null];
            } elseif (self::endsWith($cls, '\\Model\\User')) {
                $out['viewObjects'][] = ['type'=>'User', 'id'=> self::callIf($v,'getId')];
            }
        }

        if (empty($out['viewObjects'])) {
            unset($out['viewObjects']);
        }

        return $out;
    }

    /** @return array<string,mixed> */
    private static function mapEntity(object $o, array $methods): array
    {
        $m = [];
        foreach ($methods as $fn) {
            $val = self::callIf($o, $fn);
            if ($val !== null && $val !== '') {
                $m[$fn] = is_scalar($val) ? $val : null;
            }
        }
        return [
            'id'    => $m['getId']     ?? null,
            'title' => $m['getTitle']  ?? $m['getFTitle'] ?? $m['getOxtitle'] ?? null,
            'sku'   => $m['getArtNum'] ?? null,
        ];
    }

    private static function callIf(object $o, string $method): mixed
    {
        return method_exists($o, $method) ? $o->$method() : null;
    }

    private static function endsWith(string $haystack, string $needle): bool
    {
        $len = strlen($needle);
        return $len === 0 || (substr($haystack, -$len) === $needle);
    }
}
