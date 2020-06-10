<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Work\Components;

use fwkit\Wechat\ComponentBase;

class OAuth extends ComponentBase
{
    public function authorizeUrl(string $url, string $scope = 'snsapi_base', string $state = null, string $responseType = 'code'): string
    {
        $url = urlencode($url);
        return sprintf('https://open.weixin.qq.com/connect/qrconnect?appid=%s&redirect_uri=%s&response_type=%s&scope=%s&state=%s#wechat_redirect', $this->client->getAppId(), $url, $responseType, $scope, $state);
    }

    public function qyAuthorizeUrl(string $url, int $agentId = 0, string $scope = 'snsapi_base', string $state = null, string $responseType = 'code'): string
    {
        $url = urlencode($url);
        return sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=%s&scope=%s&agentid=%d&state=%s#wechat_redirect', $this->client->getAppId(), $url, $responseType, $scope, $agentId, $state);
    }

    public function ssoAuthorizeUrl(int $agentId, string $url, string $state = null): string
    {
        $url = urlencode($url);
        return sprintf('https://open.work.weixin.qq.com/wwopen/sso/qrConnect?appid=%s&agentid=%d&redirect_uri=%s&state=%s', $this->client->getAppId(), $agentId, $url, $state);
    }

    public function getUserInfo(string $code)
    {
        $res = $this->get('cgi-bin/user/getuserinfo', [
            'query' => [
                'code' => $code,
            ],
        ]);

        return $this->checkResponse($res, [
            'UserId' => 'userId',
            'DeviceId' => 'deviceId',
            'OpenId' => 'openId',

            'userid' => 'userId',
            'deviceid' => 'deviceId',
            'openid' => 'openId',
        ]);
    }
}
