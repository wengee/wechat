<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-15 15:54:57 +0800
 */
namespace fwkit\Wechat\Utils;

class Helper
{
    public static function isAssoc($arr): bool
    {
        if (!is_array($arr)) {
            return false;
        }

        return !ctype_digit(implode('', array_keys($array)));
    }

    public static function createNonceStr(int $length = 16): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}
