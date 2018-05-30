<?php

namespace fwkit\Wechat\Minapp\Components;

use fwkit\Wechat\ComponentBase;
use fwkit\Wechat\Utils\DataCrypt;

class Oauth extends ComponentBase
{
    private $code;

    private $openId;

    private $sessionKey;

    public function getSessionKey(string $code)
    {
        $params = [
            'appid' => $this->config->appId,
            'secret' => $this->config->appSecret,
            'js_code' => $code,
            'grant_type' => 'authorization_code',
        ];

        $res = $this->get('sns/jscode2session', false)
                    ->withQuery($params)
                    ->getJson();

        $this->throwOfficialError($res);

        $this->code = $code;
        $this->openId = $res->openId;
        $this->sessionKey = $res->sessionKey;
        return $res;
    }

    public function getUserInfo(string $encryptedData, string $iv, ?string $code = null, ?string $sessionKey = null)
    {
        if ($code && $this->code !== $code) {
            $this->getSessionKey($code);
        }

        $sessionKey = $sessionKey ?: $this->sessionKey;
        if (!$sessionKey) {
            throw new \Exception('Illegal session key.');
        }

        $crypter = new DataCrypt($this->config->appId, $sessionKey);
        $ret = $crypter->decrypt($encryptedData, $iv, $decryptedData);
        if ($ret !== 0) {
            throw new \Exception('Illegal encrypted data.');
        }

        return $decryptedData;
    }
}
