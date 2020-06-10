<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Utils;

/**
 * error code 说明.
 * <ul>
 *    <li>-40001: 签名验证错误</li>
 *    <li>-40002: xml解析失败</li>
 *    <li>-40003: sha加密生成签名失败</li>
 *    <li>-40004: encodingAesKey 非法</li>
 *    <li>-40005: corpid 校验错误</li>
 *    <li>-40006: aes 加密失败</li>
 *    <li>-40007: aes 解密失败</li>
 *    <li>-40008: 解密后得到的buffer非法</li>
 *    <li>-40009: base64加密失败</li>
 *    <li>-40010: base64解密失败</li>
 *    <li>-40011: 生成xml失败</li>
 * </ul>
 */
class ErrorCode
{
    public const OK = 0;

    public const VALIDATE_SIGNATURE_ERROR = -40001;

    public const PARSE_XML_ERROR = -40002;

    public const COMPUTE_SIGNATURE_ERROR = -40003;

    public const ILLEGAL_AES_KEY = -40004;

    public const VALIDATE_APPID_ERROR = -40005;

    public const ENCRYPT_AES_ERROR = -40006;

    public const DECRYPT_AES_ERROR = -40007;

    public const ILLEGAL_BUFFER = -40008;

    public const ENCODE_BASE64_ERROR = -40009;

    public const DECODE_BASE64_ERROR = -40010;

    public const GEN_RETURN_XML_ERROR = -40011;

    public const ILLEGAL_IV = -40012;
}
