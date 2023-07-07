<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-11-12 09:49:58 +0800
 */

namespace fwkit\Wechat\Miniprogram\Components;

use fwkit\Wechat\ComponentBase;

class Token extends ComponentBase
{
    public function getAccessToken()
    {
        $options = [
            'query' => [
                'grant_type' => 'client_credential',
                'appid'      => $this->client->getAppId(),
                'secret'     => $this->client->getAppSecret(),
            ],
        ];

        $res = $this->get('cgi-bin/token', $options, false);

        return $this->checkResponse($res, [
            'access_token' => 'accessToken',
            'expires_in'   => 'expiresIn',
        ]);
    }
}
