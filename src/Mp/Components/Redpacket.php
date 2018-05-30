<?php

namespace fwkit\Wechat\Mp\Components;

use fwkit\Collection;
use fwkit\Utils;
use fwkit\Wechat\ComponentBase;

class Redpacket extends ComponentBase
{
    const AMT_TYPE_RAND = 'ALL_RAND';

    const SEND_URL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';

    const SEND_GROUP_URL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack';

    const GET_URL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo';

    public function send(string $openId, int $amount = 0, array $data = [], bool $group = false)
    {
        if ($amount <= 0) {
            throw new \Exception('The amount must be large than 0.');
        }

        $data['openId'] = $openId;
        $data['amount'] = $amount;
        $xml = $this->parseSendData($data, $group);
        $url = $group ? self::SEND_GROUP_URL : self::SEND_URL;
        $res = $this->post($url, false)
                    ->withBody($xml)
                    ->withSSLCert($this->config->certPath, $this->config->keyPath)
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

    public function sendGroup(string $openId, int $amount = 0, int $count = 1, array $data = [])
    {
        $data['count'] = $count;
        return $this->send($openId, $amount, $data, true);
    }

    private function parseSendData(array $data, bool $group = false)
    {
        $data += [
            'actName' => '',
            'clientIp' => '127.0.0.1',
            'mchBillNo' => '',
            'mchId' => $this->config->mchId,
            'nonceStr' => strtolower(Utils::createNonceStr(32)),
            'openId' => '',
            'remark' => '',
            'sendName' => '',
            'amount' => 0,
            'count' => 1,
            'wishing' => '',
            'appId' => $this->config->appId,
            'sceneId' => '',
            'consumeMchId' => '',
            'riskInfo' => '',
        ];

        $group && $data['amtType'] = self::AMT_TYPE_RAND;

        if (empty($data['mchBillNo'])) {
            $microTime = microtime(true) * 10000;
            $mchBillNo = 'RP' . $this->config->mchId . $microTime;
            $data['mchBillNo'] = substr($mchBillNo, 0, 28);
        }

        $data['signature'] = $this->makeSignature($data);
        return "<xml>
                <sign><![CDATA[{$data['signature']}]></sign>
                <mch_billno><![CDATA[{$data['mchBillNo']}]]></mch_billno>
                <mch_id><![CDATA[{$data['mchId']}]]></mch_id>
                <wxappid><![CDATA[{$data['appId']}]]></wxappid>
                <send_name><![CDATA[{$data['sendName']}]]></send_name>
                <re_openid><![CDATA[{$data['openId']}]]></re_openid>
                <total_amount><![CDATA[{$data['amount']}]]></total_amount>
                <total_num><![CDATA[{$data['count']}]]></total_num>
                <wishing><![CDATA[{$data['wishing']}]]></wishing>" .
                ($group ? "<amt_type><![CDATA[{$data['amtType']}]]></amt_type>" :
                    "<client_ip><![CDATA[{$data['clientIp']}]]></client_ip>") .
                "<act_name><![CDATA[{$data['actName']}]]></act_name>
                <remark><![CDATA[{$data['remark']}]]></remark>
                <scene_id><![CDATA[{$data['sceneId']}]]></scene_id>
                <consume_mch_id><![CDATA[{$data['consumeMchId']}]]></consume_mch_id>
                <nonce_str><![CDATA[{$data['nonceStr']}]]></nonce_str>
                <risk_info><![CDATA[{$data['riskInfo']}]]></risk_info>
                </xml>";
    }

    private function makeSignature(array $data)
    {
        $keys = [
            'nonceStr' => 'nonce_str',
            'mchBillNo' => 'mch_billno',
            'mchId' => 'mch_id',
            'appId' => 'wxappid',
            'sendName' => 'send_name',
            'openId' => 're_openid',
            'amount' => 'total_amount',
            'count' => 'total_num',
            'clientIp' => 'client_ip',
            'actName' => 'act_name',
            'sceneId' => 'scene_id',
            'riskInfo' => 'risk_info',
            'consumeMchId' => 'consume_mch_id',
            'amtType' => 'amt_type',
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
