<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:54:42 +0800
 */

namespace fwkit\Wechat\ThirdParty\Components;

use fwkit\Wechat\ComponentBase;

class Token extends ComponentBase
{
    public function getAccessToken()
    {
        $options = [
            'json' => [
                'component_appid'           => $this->client->getAppId(),
                'component_appsecret'       => $this->client->getAppSecret(),
                'component_verify_ticket'   => $this->client->getComponentVerifyTicket(),
            ],
        ];

        $res = $this->post('cgi-bin/component/api_component_token', $options, false);
        return $this->checkResponse($res, [
            'component_access_token'    => 'accessToken',
            'expires_in'                => 'expiresIn',
        ]);
    }
}
