<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-14 15:17:59 +0800
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
}
