<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Work\Components;

use fwkit\Wechat\ComponentBase;

class Token extends ComponentBase
{
    public function getAccessToken()
    {
        $options = [
            'query' => [
                'corpid' => $this->client->getAppId(),
                'corpsecret' => $this->client->getAppSecret(),
            ],
        ];

        $res = $this->get('cgi-bin/gettoken', $options, false);
        return $this->checkResponse($res, [
            'access_token' => 'accessToken',
            'expires_in' => 'expiresIn',
        ]);
    }
}
