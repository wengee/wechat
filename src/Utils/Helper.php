<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Utils;

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
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, random_int(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}
