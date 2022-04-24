<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-04-24 14:18:25 +0800
 */

namespace fwkit\Wechat\Utils;

use SimpleXMLElement;

class Helper
{
    public static function isAssoc($arr): bool
    {
        if (!is_array($arr)) {
            return false;
        }

        return !ctype_digit(implode('', array_keys($arr)));
    }

    public static function createNonceStr(int $length = 16): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str   = '';
        for ($i = 0; $i < $length; ++$i) {
            $str .= substr($chars, random_int(0, strlen($chars) - 1), 1);
        }

        return $str;
    }

    /**
     * @return array|string
     */
    public static function parseXmlElement(SimpleXMLElement $xml, ?int $case = CASE_LOWER)
    {
        if (0 === $xml->count()) {
            return (string) $xml;
        }

        $ret = [];
        foreach ($xml as $key => $node) {
            if (CASE_LOWER === $case) {
                $key = strtolower($key);
            } elseif (CASE_UPPER === $case) {
                $key = strtoupper($key);
            }

            $ret[$key] = self::parseXmlElement($node, $case);
        }

        return $ret;
    }

    public static function arrGet($data, string $key, $default = null)
    {
        if (!is_array($data)) {
            return $default;
        }

        if (false === strpos($key, '.')) {
            return array_key_exists($key, $data) ? $data[$key] : $default;
        }

        $value = $data;
        $keys  = explode('.', $key);
        foreach ($keys as $k) {
            if (!is_array($value) || !array_key_exists($k, $value)) {
                return $default;
            }

            $value = $value[$k];
        }

        return $value;
    }
}
