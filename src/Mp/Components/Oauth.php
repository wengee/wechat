<?php

namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class Oauth extends ComponentBase
{
    const AUTHORIZE_URL = 'https://open.weixin.qq.com/connect/oauth2/authorize';

    const ACCESS_TOKEN_URL = 'https://api.weixin.qq.com/sns/oauth2/access_token';

    const COMPONENT_ACCESS_TOKEN_URL = 'https://api.weixin.qq.com/sns/oauth2/component/access_token';

    const REFRESH_TOKEN_URL = 'https://api.weixin.qq.com/sns/oauth2/refresh_token';

    const COMPONENT_REFRESH_TOKEN_URL = 'https://api.weixin.qq.com/sns/oauth2/component/refresh_token';

    const USERINFO_URL = 'https://api.weixin.qq.com/sns/userinfo';

    const CHECK_URL = 'https://api.weixin.qq.com/sns/auth';

    private $accessToken;

    private $openId;

    public function getAuthorizeUrl(string $redirectUrl, string $scope = 'snsapi_base', string $state = '')
    {
        return sprintf(
            '%s?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=%s#wechat_redirect',
            self::AUTHORIZE_URL,
            rawurlencode($redirectUrl),
            $scope,
            $state
        );
    }

    public function getAccessToken(string $code)
    {
        $params = [
            'appid' => $this->config->appId,
            'secret' => $this->config->appSecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
        ];

        $res = $this->get(self::ACCESS_TOKEN_URL, false)
                    ->withQuery($params)
                    ->getJson();

        $this->throwOfficialError($res);

        $this->accessToken = $res->accessToken;
        $this->openId = $res->openId;
        return $res;
    }

    public function refreshToken(string $refreshToken)
    {
        $params = [
            'appid' => $this->config->appId,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];

        $res = $this->get(self::REFRESH_TOKEN_URL, false)
                    ->withQuery($params)
                    ->getJson();

        return $this->throwOfficialError($res);
    }

    public function getUserInfo(?string $openId = null, string $lang = 'zh_CN')
    {
        $openId = $openId ?: $this->openId;
        $res = $this->get(self::USERINFO_URL, $this->accessToken)
                    ->withQuery(['openid' => $openId, 'lang' => $lang])
                    ->getJson();

        return $this->throwOfficialError($res);
    }

    public function check(?string $accessToken = null, ?string $openId = null)
    {
        $accessToken = $accessToken ?: $this->accessToken;
        $openId = $openId ?: $this->openId;

        $res = $this->get(self::CHECK_URL, $accessToken)
                    ->withQuery(['openid' => $openId])
                    ->getJson();

        return $this->throwOfficialError($res);
    }
}
