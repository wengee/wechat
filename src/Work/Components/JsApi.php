<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-21 11:31:52 +0800
 */
namespace fwkit\Wechat\Work\Components;

use fwkit\Wechat\ComponentBase;
use fwkit\Wechat\Utils\Cache;
use fwkit\Wechat\Utils\Helper;

class JsApi extends ComponentBase
{
    public function getTicket(bool $agentConfig = false)
    {
        if ($agentConfig) {
            $res = $this->get('cgi-bin/ticket/get', [
                'query' => ['type' => 'agent_config'],
            ]);
        } else {
            $res = $this->get('cgi-bin/get_jsapi_ticket');
        }

        return $this->checkResponse($res, [
            'expires_in' => 'expiresIn',
        ]);
    }

    public function signature(string $url, bool $agentConfig = false): array
    {
        $ticket = $this->safeGetTicket($agentConfig);
        if (empty($ticket)) {
            throw new \Exception('Can not fetch jsapi ticket');
        }

        if ($pos = strpos($url, '#')) {
            $url = substr($url, 0, $pos);
        }

        $nonceStr = Helper::createNonceStr();
        $timestamp = time();
        $tmpStr = sprintf(
            'jsapi_ticket=%s&noncestr=%s&timestamp=%d&url=%s',
            $ticket,
            $nonceStr,
            $timestamp,
            $url
        );

        $signature = sha1($tmpStr);
        return [
            'url' => $url,
            'appId' => $this->client->getAppId(),
            'nonceStr' => $nonceStr,
            'timestamp' => $timestamp,
            'signature' => $signature,
        ];
    }

    protected function safeGetTicket(bool $agentConfig = false): string
    {
        $appId = $this->client->getAppId();
        $cacheKey = $agentConfig ? 'agentConfigJsApiTicket' : 'jsApiTicket';
        $ticket = Cache::get($appId, $cacheKey);
        if (empty($ticket)) {
            try {
                $res = $this->getTicket($agentConfig);
            } catch (\Exception $e) {
                return '';
            }

            $ticket = $res->ticket;
            Cache::set($appId, $cacheKey, $ticket, $res->expiresIn - 600);
        }

        return $ticket ?: '';
    }
}
