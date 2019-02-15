<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-15 16:19:17 +0800
 */
namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class OAuth extends ComponentBase
{
    public function authorizeUrl(string $url, string $scope = 'snsapi_base', string $state = null, string $responseType = 'code'): string
    {
        $urlPrefix = ($scope == 'snsapi_login') ?
            'https://open.weixin.qq.com/connect/qrconnect' :
            'https://open.weixin.qq.com/connect/oauth2/authorize';

        $url = urlencode($url);
        return sprintf('%s?appid=%s&redirect_uri=%s&response_type=%s&scope=%s&state=%s#wechat_redirect', $urlPrefix, $this->client->getAppId(), $url, $responseType, $scope, $state);
    }

    public function getAccessToken(string $code, string $grantType = 'authorization_code')
    {
        $res = $this->get('sns/oauth2/access_token', [
            'query' => [
                'appid' => $this->client->getAppId(),
                'secret' => $this->client->getAppSecret(),
                'code' => $code,
                'grant_type' => $grantType,
            ],
        ]);

        return $this->checkResponse($res, [
            'openid' => 'openId',
            'unionid' => 'unionId',
            'access_token' => 'accessToken',
            'refresh_token' => 'refreshToken',
        ]);
    }

    public function refreshToken(string $refreshToken, string $grantType = 'refresh_token')
    {
        $res = $this->get('sns/oauth2/refresh_token', [
            'query' => [
                'appid' => $this->client->getAppId(),
                'grant_type' => $grantType,
                'refresh_token' => $refreshToken,
            ],
        ]);

        return $this->checkResponse($res, [
            'openid' => 'openId',
            'unionid' => 'unionId',
            'access_token' => 'accessToken',
            'refresh_token' => 'refreshToken',
        ]);
    }

    public function getOpenId(string $code)
    {
        $token = $this->getAccessToken($code);
        return $token->openId;
    }

    public function getUserInfo(string $code, string $lang = 'zh_CN')
    {
        $token = $this->getAccessToken($code);

        $res = $this->get('sns/userinfo', [
            'query' => [
                'openid' => $token->openId,
                'lang' => $lang,
            ],
        ], $token->accessToken);

        return $this->checkResponse($res, [
            'openid' => 'openId',
            'unionid' => 'unionId',
            'headimgurl' => 'headImgUrl',
        ]);
    }
}
