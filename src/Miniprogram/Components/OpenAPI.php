<?php

declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2024-12-04 09:48:28 +0800
 */

namespace fwkit\Wechat\Miniprogram\Components;

use fwkit\Wechat\ComponentBase;

class OpenAPI extends ComponentBase
{
    public function clearQuota(?string $appId = null): void
    {
        if (null === $appId) {
            $appId = $this->client->getAppId();
        }

        $res = $this->post('cgi-bin/clear_quota', [
            'json' => [
                'appid' => $appId,
            ],
        ]);

        $this->checkResponse($res);
    }

    public function getApiQuota(string $path)
    {
        $res = $this->post('cgi-bin/openapi/quota/get', [
            'json' => [
                'cgi_path' => $path,
            ],
        ]);

        return $this->checkResponse($res, [
            'daily_limit'          => 'dailyLimit',
            'rate_limit'           => 'rateLimit',
            'call_count'           => 'callCount',
            'refresh_second'       => 'refreshSecond',
            'component_rate_limit' => 'componentRateLimit',
        ]);
    }

    public function getRidInfo(string $rid)
    {
        $res = $this->post('cgi-bin/openapi/rid/get', [
            'json' => ['rid' => $rid],
        ]);

        return $this->checkResponse($res, [
            'invoke_time'   => 'invokeTime',
            'cost_in_ms'    => 'cost',
            'request_url'   => 'requestUrl',
            'request_body'  => 'requestBody',
            'response_body' => 'responseBody',
            'client_ip'     => 'clientIp',
        ]);
    }

    public function clearQuotaByAppSecret(?string $appId = null, ?string $appSecret = null): void
    {
        if (null === $appId) {
            $appId = $this->client->getAppId();
        }

        if (null === $appSecret) {
            $appSecret = $this->client->getAppSecret();
        }

        $res = $this->post('cgi-bin/clear_quota/v2', [
            'json' => [
                'appid'     => $appId,
                'appsecret' => $appSecret,
            ],
        ], false);

        $this->checkResponse($res);
    }
}
