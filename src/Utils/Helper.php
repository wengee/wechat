<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-05-29 18:24:16 +0800
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
}
