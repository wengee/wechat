<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-04 11:49:06 +0800
 */

namespace fwkit\Wechat\ThirdParty\Components;

use fwkit\Wechat\ComponentBase;

class OAuth extends ComponentBase
{
    public function createPreAuthCode()
    {
        $res = $this->post('cgi-bin/component/api_create_preauthcode', [
            'json' => [
                'component_appid' => $this->client->getAppId(),
            ],
        ]);

        return $this->checkResponse($res, [
            'pre_auth_code' => 'preAuthCode',
            'expires_in'    => 'expiresIn',
        ]);
    }

    public function authorizeUrl(string $url, int $authType = 3, bool $isPC = true, ?string $bizAppId = null, ?string $preAuthCode = null): string
    {
        if ($preAuthCode === null) {
            $res = $this->createPreAuthCode();
            $preAuthCode = $res['preAuthCode'];
        }

        $originUrl = $isPC ?
            'https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid=%s&pre_auth_code=%s&redirect_uri=%s&auth_type=%d&biz_appid=%s' :
            'https://mp.weixin.qq.com/safe/bindcomponent?action=bindcomponent&no_scan=1&component_appid=%s&pre_auth_code=%s&redirect_uri=%s&auth_type=%d&biz_appid=%s#wechat_redirect';

        $url = urlencode($url);
        return sprintf($originUrl, $this->client->getAppId(), $preAuthCode, $url, $authType, $bizAppId);
    }

    public function queryAuth(string $code)
    {
        $res = $this->post('cgi-bin/component/api_query_auth', [
            'json' => [
                'component_appid'       => $this->client->getAppId(),
                'authorization_code'    => $code,
            ],
        ]);

        return $this->checkResponse($res, [
            'authorization_info'        => 'authorizationInfo',
            'authorizer_appid'          => 'authorizerAppId',
            'authorizer_access_token'   => 'accessToken',
            'expires_in'                => 'expiresIn',
            'authorizer_refresh_token'  => 'refreshToken',
            'func_info'                 => 'funcInfo',
            'funcscope_category'        => 'funcscopeCategory',
        ]);
    }

    public function refreshToken(string $authorizerAppId, string $authorizerRefreshToken)
    {
        $res = $this->post('cgi-bin/component/api_authorizer_token', [
            'json' => [
                'component_appid'           => $this->client->getAppId(),
                'authorizer_appid'          => $authorizerAppId,
                'authorizer_refresh_token'  => $authorizerRefreshToken,
            ],
        ]);

        return $this->checkResponse($res, [
            'authorizer_access_token'   => 'accessToken',
            'expires_in'                => 'expiresIn',
            'authorizer_refresh_token'  => 'refreshToken',
        ]);
    }

    public function getAuthorizerInfo(string $authorizerAppId)
    {
        $res = $this->post('cgi-bin/component/api_get_authorizer_info', [
            'json' => [
                'component_appid'   => $this->client->getAppId(),
                'authorizer_appid'  => $authorizerAppId,
            ],
        ]);

        return $this->checkResponse($res, [
            'authorization_info'    => 'authorizationInfo',
            'authorizer_info'       => 'authorizerInfo',
            'nick_name'             => 'nickname',
            'head_img'              => 'headImg',
            'service_type_info'     => 'serviceTypeInfo',
            'verify_type_info'      => 'verifyTypeInfo',
            'user_name'             => 'username',
            'principal_name'        => 'principalName',
            'business_info'         => 'businessInfo',
            'qrcode_url'            => 'qrcodeUrl',
            'authorization_appid'   => 'authorizationAppId',
            'func_info'             => 'funcInfo',
            'funcscope_category'    => 'funcscopeCategory',
        ]);
    }

    public function list(int $offset = 0, int $count = 100)
    {
        $res = $this->post('cgi-bin/component/api_get_authorizer_list', [
            'json' => [
                'component_appid'   => $this->client->getAppId(),
                'offset'            => $offset,
                'count'             => $count,
            ],
        ]);

        return $this->checkResponse($res, [
            'total_count'       => 'total',
            'authorizer_appid'  => 'authorizerAppId',
            'refresh_token'     => 'refreshToken',
            'auth_time'         => 'authTime',
        ]);
    }
}
