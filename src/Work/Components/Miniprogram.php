<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-12-24 14:53:03 +0800
 */
namespace fwkit\Wechat\Work\Components;

use fwkit\Wechat\ComponentBase;

class Miniprogram extends ComponentBase
{
    public function getSessionKey(string $code)
    {
        $res = $this->get('sns/jscode2session', [
            'query' => [
                'js_code'       => $code,
                'grant_type'    => 'authorization_code',
            ],
        ]);

        $res = $this->checkResponse($res, [
            'corpid'        => 'corpId',
            'userid'        => 'userId',
            'session_key'   => 'sessionKey',
        ]);

        $this->code = $code;
        $this->openId = $res->openId;
        $this->sessionKey = $res->sessionKey;
        return $res;
    }
}
