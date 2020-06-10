<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
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
