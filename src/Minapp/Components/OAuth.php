<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-12-24 14:58:41 +0800
 */
namespace fwkit\Wechat\Minapp\Components;

use fwkit\Wechat\ComponentBase;
use fwkit\Wechat\OfficialError;
use fwkit\Wechat\Utils\DataCrypt;

class OAuth extends ComponentBase
{
    private $code;

    private $openId;

    private $sessionKey;

    public function getSessionKey(string $code)
    {
        $res = $this->get('sns/jscode2session', [
            'query' => [
                'appid' => $this->client->getAppId(),
                'secret' => $this->client->getAppSecret(),
                'js_code' => $code,
                'grant_type' => 'authorization_code',
            ],
        ], false);

        $res = $this->checkResponse($res, [
            'expires_in' => 'expiresIn',
            'openid' => 'openId',
            'unionid' => 'unionId',
            'session_key' => 'sessionKey',
        ]);

        $this->code = $code;
        $this->openId = $res->openId;
        $this->sessionKey = $res->sessionKey;
        return $res;
    }

    public function getUserInfo(string $encryptedData, string $iv, ?string $sessionKey = null)
    {
        $data = $this->decryptData($encryptedData, $iv, $sessionKey);
        if (!is_array($data) || empty($data)) {
            throw new OfficialError('Data is empty.');
        }

        $data = $this->transformKeys($data, [
            'nickName' => 'nickname',
            'appid' => 'appId',
        ]);
        return $this->makeCollection($data);
    }

    public function decryptData(string $encryptedData, string $iv, ?string $sessionKey = null)
    {
        $sessionKey = $sessionKey ?: $this->sessionKey;
        if (!$sessionKey) {
            throw new OfficialError('Illegal session key.');
        }

        $crypter = new DataCrypt($this->client->getAppId(), $sessionKey);
        $ret = $crypter->decrypt($encryptedData, $iv, $decryptedData);
        if ($ret !== 0) {
            throw new OfficialError('Illegal encrypted data.');
        }

        $data = json_decode($decryptedData, true);

        $errcode = json_last_error();
        if ($errcode !== JSON_ERROR_NONE) {
            throw new OfficialError('Parse json error: ' . json_last_error_msg(), $errcode);
        }

        return $data;
    }
}
