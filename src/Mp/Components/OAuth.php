<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-12-05 16:21:23 +0800
 */

namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class OAuth extends ComponentBase
{
    public function authorizeUrl(string $url, string $scope = 'snsapi_base', string $state = null, string $responseType = 'code'): string
    {
        $urlPrefix = ('snsapi_login' == $scope) ?
            'https://open.weixin.qq.com/connect/qrconnect' :
            'https://open.weixin.qq.com/connect/oauth2/authorize';

        $url         = urlencode($url);
        $extra       = '';
        $openClient = $this->client->getOpenClient();
        if ($openClient) {
            $extra = sprintf('&component_appid=%s', $openClient->getAppId());
        }

        return sprintf('%s?appid=%s&redirect_uri=%s&response_type=%s&scope=%s&state=%s%s#wechat_redirect', $urlPrefix, $this->client->getAppId(), $url, $responseType, $scope, $state, $extra);
    }

    public function getAccessToken(string $code, string $grantType = 'authorization_code')
    {
        $openClient = $this->client->getOpenClient();
        if ($openClient) {
            $url   = 'sns/oauth2/component/access_token';
            $query = [
                'appid'                  => $this->client->getAppId(),
                'code'                   => $code,
                'grant_type'             => $grantType,
                'component_appid'        => $openClient->getAppId(),
                'component_access_token' => $openClient->getAccessToken(),
            ];
        } else {
            $url   = 'sns/oauth2/access_token';
            $query = [
                'appid'      => $this->client->getAppId(),
                'secret'     => $this->client->getAppSecret(),
                'code'       => $code,
                'grant_type' => $grantType,
            ];
        }

        $res = $this->get($url, ['query' => $query], false);

        return $this->checkResponse($res, [
            'openid'        => 'openId',
            'unionid'       => 'unionId',
            'expires_in'    => 'expiresIn',
            'access_token'  => 'accessToken',
            'refresh_token' => 'refreshToken',
        ]);
    }

    public function refreshToken(string $refreshToken, string $grantType = 'refresh_token')
    {
        $openClient = $this->client->getOpenClient();
        if ($openClient) {
            $url   = 'sns/oauth2/component/refresh_token';
            $query = [
                'appid'                  => $this->client->getAppId(),
                'grant_type'             => $grantType,
                'refresh_token'          => $refreshToken,
                'component_appid'        => $openClient->getAppId(),
                'component_access_token' => $openClient->getAccessToken(),
            ];
        } else {
            $url   = 'sns/oauth2/refresh_token';
            $query = [
                'appid'         => $this->client->getAppId(),
                'grant_type'    => $grantType,
                'refresh_token' => $refreshToken,
            ];
        }

        $res = $this->get($url, ['query' => $query], false);

        return $this->checkResponse($res, [
            'openid'        => 'openId',
            'unionid'       => 'unionId',
            'access_token'  => 'accessToken',
            'refresh_token' => 'refreshToken',
        ]);
    }

    public function getOpenId(string $code)
    {
        $token = $this->getAccessToken($code);

        return $token['openId'];
    }

    public function getUserInfo(string $code, string $lang = 'zh_CN')
    {
        $token = $this->getAccessToken($code);

        $res = $this->get('sns/userinfo', [
            'query' => [
                'openid' => $token->openId,
                'lang'   => $lang,
            ],
        ], $token->accessToken);

        return $this->checkResponse($res, [
            'openid'     => 'openId',
            'unionid'    => 'unionId',
            'headimgurl' => 'headImgUrl',
        ]);
    }
}
