<?php

namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class Token extends ComponentBase
{
    public function getAccessToken()
    {
        $params = [
            'grant_type' => 'client_credential',
            'appid' => $this->config->appId,
            'secret' => $this->config->appSecret,
        ];

        $res = $this->get('token', false)
                    ->withQuery($params)
                    ->getJson();

        return $this->throwOfficialError($res);
    }
}
