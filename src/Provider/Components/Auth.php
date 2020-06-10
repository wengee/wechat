<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Provider\Components;

use fwkit\Wechat\ComponentBase;

class Auth extends ComponentBase
{
    public function getPreAuthCode()
    {
        $res = $this->post('cgi-bin/component/api_create_preauthcode', [
            'json' => [
                'component_appid' => $this->client->getAppId(),
            ],
        ]);

        $res = $this->checkResponse($res);
        return $res->get('pre_auth_code');
    }

    public function authorizeUrl(string $url)
    {
        return 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?' . http_build_query([
            'component_appid' => $this->client->getAppId(),
            'pre_auth_code' => $this->getPreAuthCode(),
            'redirect_uri' => $url,
        ]);
    }

    public function queryAuth(string $code)
    {
        $res = $this->post('cgi-bin/component/api_query_auth', [
            'json' => [
                'component_appid' => $this->client->getAppId(),
                'authorization_code' => $code,
            ],
        ]);

        return $this->checkResponse($res, [
            'authorization_info' => 'authorizationInfo',
            'authorizer_appid' => 'authorizerAppid',
            'authorizer_access_token' => 'authorizerAccessToken',
            'expires_in' => 'expiresIn',
            'authorizer_refresh_token' => 'authorizerRefreshToken',
            'func_info' => 'funcInfo',
            'funcscope_category' => 'funcScopeCategory',
        ]);
    }
}
