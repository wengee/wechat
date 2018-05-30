<?php

namespace fwkit\Wechat\Mp\Components;

use fwkit\Collection;
use fwkit\Utils;
use fwkit\Wechat\ComponentBase;

class Jsapi extends ComponentBase
{
    public function getTicket(string $type = 'jsapi')
    {
        $cache = $this->config->cache;
        $cacheKey = 'ticket-' . $type . '-' . $this->config->appId;
        if (is_callable($cache)) {
            $ticket = Utils::execute($cache, [$cacheKey]);
            if ($ticket) return $ticket;
        }

        try {
            $res = $this->get('ticket/getticket')
                        ->withQuery(['type' => $type])
                        ->getJson();

            $this->throwOfficialError($res);
            $ticket = $res->ticket;
            if ($ticket && is_callable($cache)) {
                $expireIn = (int) $res->expiresIn;
                Utils::execute($cache, [$cacheKey, $ticket, $expireIn]);
            }
        } catch (\Exception $e) {
            return null;
        }

        return $ticket;
    }

    public function sign(string $url)
    {
        $ticket = $this->getTicket();
        $nonceStr = strval(Utils::createNonceStr());
        $timestamp = time();

        $str = sprintf('jsapi_ticket=%s&noncestr=%s&timestamp=%s&url=%s', $ticket, $nonceStr, $timestamp, $url);
        $signature = sha1($str);

        return new Collection([
            'url' => $url,
            'appId' => $this->config->appId,
            'nonceStr' => $nonceStr,
            'timestamp' => (int) $timestamp,
            'signature' => $signature,
        ]);
    }
}
