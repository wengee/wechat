<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class Token extends ComponentBase
{
    public function getAccessToken()
    {
        $thirdClient = $this->client->getThirdClient();
        if ($thirdClient) {
            return $thirdClient->getAuthorizerAccessToken($this->client->getAppId());
        }

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
