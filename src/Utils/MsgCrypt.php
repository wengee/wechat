<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2018-11-01 17:51:05 +0800
 */
namespace Wechat\Utils;

/**
 * 对公众平台发送给公众账号的消息加解密示例代码.
 *
 * @copyright Copyright (c) 1998-2014 Tencent Inc.
 */

/**
 * 1.第三方回复加密消息给公众平台；
 * 2.第三方收到公众平台发送的消息，验证消息的安全性，并对消息进行解密。
 */
class MsgCrypt
{
    private $sToken;

    private $sEncodingAESKey;

    private $sAppId;

    private $pc;

    /**
     * 构造函数
     * @param $token string 公众平台上，开发者设置的token
     * @param $encodingAesKey string 公众平台上，开发者设置的EncodingAESKey
     * @param $appid string 公众平台的appid
     */
    public function __construct(string $token, string $encodingAESKey, string $appId)
    {
        $this->sToken = $token;
        $this->sEncodingAESKey = $encodingAESKey;
        $this->sAppId = $appId;
        $this->pc = new Prpcrypt($encodingAESKey);
    }

    /*
    *验证URL
    *@param sMsgSignature: 签名串，对应URL参数的msg_signature
    *@param sTimeStamp: 时间戳，对应URL参数的timestamp
    *@param sNonce: 随机串，对应URL参数的nonce
    *@param sEchoStr: 随机串，对应URL参数的echostr
    *@param sReplyEchoStr: 解密之后的echostr，当return返回0时有效
    *@return：成功0，失败返回对应的错误码
    */
    public function verifyUrl(string $sMsgSignature, string $sTimeStamp, string $sNonce, string $sEchoStr, &$sReplyEchoStr)
    {
        if (strlen($this->sEncodingAESKey) != 43) {
            return ErrorCode::ILLEGAL_AES_KEY;
        }

        //verify msg_signature
        [$ret, $signature] = SHA1::signature($this->sToken, $sTimeStamp, $sNonce, $sEchoStr);
        if ($ret !== 0) {
            return $ret;
        }

        if ($signature !== $sMsgSignature) {
            return ErrorCode::VALIDATE_SIGNATURE_ERROR;
        }

        [$ret, $sReplyEchoStr] = $this->pc->decrypt($sEchoStr, $this->sAppId);
        if ($ret !== 0) {
            return $ret;
        }

        return ErrorCode::OK;
    }

    /**
     * 将公众平台回复用户的消息加密打包.
     * <ol>
     *    <li>对要发送的消息进行AES-CBC加密</li>
     *    <li>生成安全签名</li>
     *    <li>将消息密文和安全签名打包成xml格式</li>
     * </ol>
     *
     * @param $replyMsg string 公众平台待回复用户的消息，xml格式的字符串
     * @param $timeStamp string 时间戳，可以自己生成，也可以用URL参数的timestamp
     * @param $nonce string 随机串，可以自己生成，也可以用URL参数的nonce
     * @param &$encryptMsg string 加密后的可以直接回复用户的密文，包括msg_signature, timestamp, nonce, encrypt的xml格式的字符串,
     *                      当return返回0时有效
     *
     * @return int 成功0，失败返回对应的错误码
     */
    public function encrypt(string $sReplyMsg, int $sTimeStamp, string $sNonce, &$sEncryptMsg)
    {
        //加密
        [$ret, $encrypted] = $this->pc->encrypt($sReplyMsg, $this->sAppId);
        $ret = $array[0];
        if ($ret !== 0) {
            return $ret;
        }

        //生成安全签名
        [$ret, $signature] = SHA1::signature($this->sToken, $sTimeStamp, $sNonce, $encrypted);
        if ($ret !== 0) {
            return $ret;
        }

        //生成发送的xml
        $sEncryptMsg = XMLParse::generate($encrypted, $signature, $sTimeStamp, $sNonce);
        return ErrorCode::OK;
    }

    /**
     * 检验消息的真实性，并且获取解密后的明文.
     * <ol>
     *    <li>利用收到的密文生成安全签名，进行签名验证</li>
     *    <li>若验证通过，则提取xml中的加密消息</li>
     *    <li>对消息进行解密</li>
     * </ol>
     *
     * @param $msgSignature string 签名串，对应URL参数的msg_signature
     * @param $timestamp string 时间戳 对应URL参数的timestamp
     * @param $nonce string 随机串，对应URL参数的nonce
     * @param $postData string 密文，对应POST请求的数据
     * @param &$msg string 解密后的原文，当return返回0时有效
     *
     * @return int 成功0，失败返回对应的错误码
     */
    public function decrypt(string $sMsgSignature, int $sTimeStamp, string $sNonce, string $sPostData, &$sMsg)
    {
        //提取密文
        [$ret, $encrypted, $toUserName] = XMLParse::extract($sPostData);
        if ($ret !== 0) {
            return $ret;
        }

        //验证安全签名
        [$ret, $signature] = SHA1::signature($this->sToken, $sTimeStamp, $sNonce, $encrypted);
        if ($ret !== 0) {
            return $ret;
        }

        if ($signature !== $sMsgSignature) {
            return ErrorCode::VALIDATE_SIGNATURE_ERROR;
        }

        [$ret, $sMsg] = $this->pc->decrypt($encrypted, $this->sAppId);
        if ($ret !== 0) {
            return $ret;
        }

        return ErrorCode::OK;
    }
}
