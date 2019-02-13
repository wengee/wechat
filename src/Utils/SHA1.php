<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2018-11-01 17:51:13 +0800
 */
namespace fwkit\Wechat\Utils;

/**
 * SHA1 class
 *
 * 计算公众平台的消息签名接口.
 */
class SHA1
{
    /**
     * 用SHA1算法生成安全签名
     * @param string $token 票据
     * @param string $timestamp 时间戳
     * @param string $nonce 随机字符串
     * @param string $encrypt 密文消息
     */
    public static function signature($token, $timestamp, $nonce, $data)
    {
        try {
            $arr = [$data, $token, $timestamp, $nonce];
            sort($arr, SORT_STRING);
            $str = implode($arr);
            return [ErrorCode::OK, sha1($str)];
        } catch (Exception $e) {
            return [ErrorCode::COMPUTE_SIGNATURE_ERROR, null];
        }
    }
}
