<?php

namespace fwkit\Wechat\Mp\Components;

use fwkit\Collection;
use fwkit\Utils;
use fwkit\Wechat\ComponentBase;

class Pay extends ComponentBase
{
    const TRADE_JSAPI = 'JSAPI';

    const TRADE_NATIVE = 'NATIVE';

    const TRADE_APP = 'APP';

    const FEE_CNY = 'CNY';

    const SIGN_MD5 = 'MD5';

    const SIGN_HMAC_SHA256 = 'HMAC-SHA256';

    const UNIFIED_ORDER_URL = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

    public function order(array $data = [], bool $noCredit = false)
    {
        $data += [
            'appId' => $this->config->appId,
            'mchId' => $this->config->mchId,
            'deviceInfo' => '',
            'nonceStr' => Utils::createNonceStr(32),
            'body' => '',
            'detail' => '',
            'attach' => '',
            'outTradeNo' => '',
            'feeType' => self::FEE_CNY,
            'totalFee' => 0,
            'clientIp' => '127.0.0.1',
            'timeStart' => '',
            'timeExpire' => '',
            'goodsTag' => '',
            'notifyUrl' => '',
            'tradeType' => self::TRADE_JSAPI,
            'productId' => '',
            'limitPay' => $noCredit ? 'no_credit' : '',
            'openId' => '',
            'sceneInfo' => '',
        ];

        $data['sign'] = $this->makeSignature($data);
        $data['signType'] = self::SIGN_MD5;

        $xml = "<xml>
                <appid><![CDATA[{$data['appId']}]]></appid>
                <mch_id><![CDATA[{$data['mchId']}]]></mch_id>
                <device_info><![CDATA[{$data['deviceInfo']}]]></device_info>
                <nonce_str><![CDATA[{$data['nonceStr']}]]></nonce_str>
                <sign><![CDATA[{$data['sign']}]></sign>
                <sign_type><![CDATA[{$data['signType']}]></sign_type>
                <body><![CDATA[{$data['body']}]></body>
                <detail><![CDATA[{$data['detail']}]></detail>
                <attach><![CDATA[{$data['attach']}]></attach>
                <out_trade_no><![CDATA[{$data['outTradeNo']}]></out_trade_no>
                <fee_type><![CDATA[{$data['feeType']}]></fee_type>
                <total_fee><![CDATA[{$data['totalFee']}]></total_fee>
                <spbill_create_ip><![CDATA[{$data['clientIp']}]></spbill_create_ip>
                <time_start><![CDATA[{$data['timeStart']}]></time_start>
                <time_expire><![CDATA[{$data['timeExpire']}]></time_expire>
                <goods_tag><![CDATA[{$data['goodsTag']}]></goods_tag>
                <notify_url><![CDATA[{$data['notifyUrl']}]></notify_url>
                <trade_type><![CDATA[{$data['tradeType']}]></trade_type>
                <product_id><![CDATA[{$data['productId']}]></product_id>
                <limit_pay><![CDATA[{$data['limitPay']}]></limit_pay>
                <openid><![CDATA[{$data['openId']}]></openid>
                <scene_info><![CDATA[{$data['sceneInfo']}]></scene_info>
                </xml>";

        $res = $this->post(self::UNIFIED_ORDER_URL, false)
                    ->withBody($xml)
                    ->getText();

        if (empty($res)) {
            throw new \Exception('Unknown error.');
        }

        $result = (array) simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
        return new Collection([
            'rawXml' => $res,
            'result' => $result,
        ], Collection::ALPHA_ONLY);
    }

    public function query()
    {
    }

    private function makeSignature(array $data)
    {
        $keys = [
            'appId' => 'appid',
            'mchId' => 'mch_id',
            'deviceInfo' => 'device_info',
            'nonceStr' => 'nonce_str',
            'outTradeNo' => 'out_trade_no',
            'feeType' => 'fee_type',
            'totalFee' => 'total_fee',
            'clientIp' => 'spbill_create_ip',
            'timeStart' => 'time_start',
            'timeExpire' => 'time_expire',
            'goodsTag' => 'goods_tag',
            'notifyUrl' => 'notify_url',
            'tradeType' => 'trade_type',
            'productId' => 'product_id',
            'limitPay' => 'limit_pay',
            'openId' => 'openid',
            'sceneInfo' => 'scene_info',
        ];

        $arr = [];
        array_walk($data, function (&$item, $key) use (&$arr, &$keys) {
            if ($item !== '') {
                $arr[isset($keys[$key]) ? $keys[$key] : $key] = $item;
            }
        });

        ksort($arr);
        $signData = urldecode(http_build_query($arr, null, '&', PHP_QUERY_RFC3986));
        $signData .= '&key=' . $this->config->mchKey;
        return strtoupper(md5($signData));
    }
}
