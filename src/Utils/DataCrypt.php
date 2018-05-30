<?php

namespace fwkit\Wechat\Utils;

/**
 * 对微信小程序用户加密数据的解密示例代码.
 *
 * @copyright Copyright (c) 1998-2014 Tencent Inc.
 */
class DataCrypt
{
    private $appId;

    private $sessionKey;

    /**
     * 构造函数
     * @param $sessionKey string 用户在小程序登录后获取的会话密钥
     * @param $appid string 小程序的appid
     */
    public function __construct(string $appId, string $sessionKey)
    {
        $this->appId = $appId;
        $this->sessionKey = $sessionKey;
    }

    /**
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $encryptedData string 加密的用户数据
     * @param $iv string 与用户数据一同返回的初始向量
     * @param $data string 解密后的原文
     *
     * @return int 成功0，失败返回对应的错误码
     */
    public function decrypt(string $encryptedData, string $iv, string &$data)
    {
        if (strlen($this->sessionKey) != 24) return ErrorCode::ILLEGAL_AES_KEY;
        $aesKey = base64_decode($this->sessionKey);

        if (strlen($iv) != 24) return ErrorCode::ILLEGAL_IV;
        $aesIV = base64_decode($iv);
        $aesCipher = base64_decode($encryptedData);
        $result = openssl_decrypt($aesCipher, 'AES-128-CBC', $aesKey, 1, $aesIV);

        $dataObj = json_decode($result);
        if ($dataObj === null) return ErrorCode::ILLEGAL_BUFFER;
        if ($dataObj->watermark->appid !== $this->appId) return ErrorCode::ILLEGAL_BUFFER;

        $data = $result;
        return ErrorCode::OK;
    }
}
