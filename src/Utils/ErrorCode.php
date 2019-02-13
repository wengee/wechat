<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2018-11-01 17:51:01 +0800
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
    const OK = 0;

    const VALIDATE_SIGNATURE_ERROR = -40001;

    const PARSE_XML_ERROR = -40002;

    const COMPUTE_SIGNATURE_ERROR = -40003;

    const ILLEGAL_AES_KEY = -40004;

    const VALIDATE_APPID_ERROR = -40005;

    const ENCRYPT_AES_ERROR = -40006;

    const DECRYPT_AES_ERROR = -40007;

    const ILLEGAL_BUFFER = -40008;

    const ENCODE_BASE64_ERROR = -40009;

    const DECODE_BASE64_ERROR = -40010;

    const GEN_RETURN_XML_ERROR = -40011;

    const ILLEGAL_IV = -40012;
}
