<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-14 15:51:10 +0800
 */
namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class Token extends ComponentBase
{
    public function getAccessToken()
    {
        $options = [
            'query' => [
                'grant_type' => 'client_credential',
                'appid' => $this->client->getAppId(),
                'secret' => $this->client->getAppSecret(),
            ],
        ];

        $res = $this->get('cgi-bin/token', $options, false);
        return $this->checkResponse($res, [
            'access_token' => 'accessToken',
            'expires_in' => 'expiresIn',
        ]);
    }
}
