<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-12-05 16:15:33 +0800
 */

namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class OpenApi extends ComponentBase
{
    public function clearQuota(string $appId): void
    {
        $res = $this->post('cgi-bin/clear_quota', [
            'json' => [
                'appid' => $appId,
            ],
        ]);

        $this->checkResponse($res);
    }

    public function getQuota(string $cgiPath)
    {
        $res = $this->post('cgi-bin/openapi/quota/get', [
            'json' => [
                'cgi_path' => $cgiPath,
            ],
        ]);

        $res = $this->checkResponse($res, [
            'daily_limit' => 'dailyLimit',
        ]);

        return $res->get('quota');
    }

    public function getRequest(string $rid)
    {
        $res = $this->post('cgi-bin/openapi/quota/get', [
            'json' => [
                'rid' => $rid,
            ],
        ]);

        $res = $this->checkResponse($res, [
            'invoke_time'   => 'invokeTime',
            'cost_in_ms'    => 'costInMs',
            'request_url'   => 'requestUrl',
            'request_body'  => 'requestBody',
            'response_body' => 'responseBody',
            'client_ip'     => 'clientIp',
        ]);

        return $res->get('request');
    }
}
