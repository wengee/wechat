<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-16 16:43:58 +0800
 */
namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;
use fwkit\Wechat\Utils\Helper;

class JsApi extends ComponentBase
{
    public function getTicket(string $type = 'jsapi')
    {
        $res = $this->get('cgi-bin/ticket/getticket', [
            'query' => ['type' => $type],
        ]);

        return $this->checkResponse($res, [
            'expires_in' => 'expiresIn',
        ]);
    }

    public function signature(string $url): array
    {
        $ticket = $this->safeGetTicket('jsapi');
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

    public function cardSignature(string $cardId, ?string $code = null, ?string $openId = null): array
    {
        $ticket = $this->safeGetTicket('wx_card');
        if (empty($ticket)) {
            throw new \Exception('Can not fetch jsapi ticket');
        }

        $data = [
            'cardId' => $cardId,
            'ticket' => $ticket,
            'timestamp' => time(),
            'nonceStr' => Helper::createNonceStr(),
        ];
        if ($code !== null) {
            $data['code'] = $code;
        }

        if ($openId !== null) {
            $data['openid'] = $openId;
        }

        $values = array_values($data);
        sort($values, SORT_STRING);
        $data['signature'] = sha1(implode('', $values));

        return $data;
    }

    protected function safeGetTicket(string $type = 'jsapi'): string
    {
        $cacheKey = $type . 'Ticket';
        $ticket = $this->client->cacheGet($cacheKey);
        if (empty($ticket)) {
            try {
                $res = $this->getTicket($type);
            } catch (\Exception $e) {
                return '';
            }

            $ticket = $res->ticket;
            $this->client->cacheSet($cacheKey, $ticket, $res->expiresIn - 600);
        }

        return $ticket ?: '';
    }
}
