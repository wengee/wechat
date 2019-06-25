<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-06-25 16:29:40 +0800
 */
namespace fwkit\Wechat\Work\Components;

use fwkit\Wechat\ComponentBase;
use fwkit\Wechat\Utils\Helper;

class Redpack extends ComponentBase
{
    public function send(string $openId, int $amount = 100, array $data = [], array $mchConfig = [])
    {
        $data['re_openid'] = $openId;
        $data['total_amount'] = $amount;
        $xml = $this->toXml($data, $mchConfig);

        $res = $this->post('https://api.mch.weixin.qq.com/mmpaymkttransfers/sendworkwxredpack', [
            'body' => $xml,
            'withCert' => $mchConfig,
        ], false, 'xml');

        return $res ? $this->transformKeys($res, [
            'return_code' => 'returnCode',
            'return_msg' => 'returnMsg',
            'result_code' => 'resultCode',
            'mch_billno' => 'mchBillNo',
            'mch_id' => 'mchId',
            'wxappid' => 'appId',
            're_openid' => 'openId',
            'total_amount' => 'amount',
            'send_listid' => 'sendListId',
            'sender_name' => 'senderName',
            'sender_header_media_id' => 'senderHeaderMediaId',
        ]) : null;
    }

    public function query(string $mchBillNo, array $mchConfig = [])
    {
        $data = [
            'nonce_str' => Helper::createNonceStr(),
            'mch_billno' => $mchBillNo,
            'mch_id' => $this->client->mchConfig($mchConfig, 'mchId'),
            'appid' => $this->client->getAppId(),
        ];

        $data['sign'] = $this->signature($data, $mchConfig);
        $xml = "<xml>
                    <nonce_str><![CDATA[{$data['nonce_str']}]]></nonce_str>
                    <sign><![CDATA[{$data['sign']}]]></sign>
                    <mch_billno><![CDATA[{$data['mch_billno']}]]></mch_billno>
                    <mch_id><![CDATA[{$data['mch_id']}]]></mch_id>
                    <appid><![CDATA[{$data['appid']}]]></appid>
                </xml>";

        $res = $this->post('https://api.mch.weixin.qq.com/mmpaymkttransfers/queryworkwxredpack', [
            'body' => $xml,
            'withCert' => $mchConfig,
        ], false, 'xml');

        return $res ? $this->transformKeys($res, [
            'return_code' => 'returnCode',
            'return_msg' => 'returnMsg',
            'result_code' => 'resultCode',
            'mch_billno' => 'mchBillNo',
            'mch_id' => 'mchId',
            'detail_id' => 'detailId',
            'send_type' => 'sendType',
            'total_amount' => 'amount',
            'send_time' => 'sendTime',
            'act_name' => 'actName',
            'openid' => 'openId',
            'rcv_time' => 'rcvTime',
            'sender_name' => 'senderName',
            'sender_header_media_id' => 'senderHeaderMediaId',
        ]) : null;
    }

    private function toXml(array $data, array $mchConfig = [])
    {
        $data = $this->transformKeys($data, [
            'actName' => 'act_name',
            'clientIp' => 'client_ip',
            'billNo' => 'mch_billno',
            'mchBillNo' => 'mch_billno',
            'openId' => 're_openid',
            'senderName' => 'sender_name',
            'amount' => 'total_amount',
            'num' => 'total_num',
            'agentId' => 'agentid',
            'senderHeaderMediaId' => 'sender_header_media_id',
            'sceneId' => 'scene_id',
        ]);

        $data += [
            'nonce_str' => Helper::createNonceStr(),
            'mch_billno' => '',
            'mch_id' => $this->client->mchConfig($mchConfig, 'mchId'),
            'wxappid' => $this->client->getAppId(),
            'sender_name' => '',
            'sender_header_media_id' => '',
            're_openid' => '',
            'total_amount' => '',
            'wishing' => '',//祝福语
            'act_name' => '',
            'remark' => '',
            'scene_id' => '',
        ];

        if (empty($data['mch_billno'])) {
            $microTime = microtime(true) * 10000;
            $mchBillNo = 'RP' . $this->client->getAppId() . $microTime;
            $data['mch_billno'] = substr($mchBillNo, 0, 28);
        }

        $data['workwx_sign'] = $this->signature($data, true);
        $data['sign'] = $this->signature($data, $mchConfig);

        return "<xml>
                    <nonce_str><![CDATA[{$data['nonce_str']}]]></nonce_str>
                    <sign><![CDATA[{$data['sign']}]]></sign>
                    <mch_billno><![CDATA[{$data['mch_billno']}]]></mch_billno>
                    <mch_id><![CDATA[{$data['mch_id']}]]></mch_id>
                    <wxappid><![CDATA[{$data['wxappid']}]]></wxappid>
                    <sender_name><![CDATA[{$data['sender_name']}]]></sender_name>
                    <sender_header_media_id><![CDATA[{$data['sender_header_media_id']}]]></sender_header_media_id>
                    <re_openid><![CDATA[{$data['re_openid']}]]></re_openid>
                    <total_amount><![CDATA[{$data['total_amount']}]]></total_amount>
                    <wishing><![CDATA[{$data['wishing']}]]></wishing>
                    <act_name><![CDATA[{$data['act_name']}]]></act_name>
                    <remark><![CDATA[{$data['remark']}]]></remark>
                    <scene_id><![CDATA[{$data['scene_id']}]]></scene_id>
                    <workwx_sign><![CDATA[{$data['workwx_sign']}]]></workwx_sign>
                </xml>";
    }

    private function signature(array $data, $workWxSign = false)
    {
        $data = array_filter($data, 'strlen');
        ksort($data);
        $tmpStr = '';
        foreach ($data as $key => $value) {
            $tmpStr .= $key . '=' . $value . '&';
        }

        if ($workWxSign === true) {
            $tmpStr .= 'secret=' . $this->client->getAppSecret();
        } else {
            $mchConfig = is_array($workWxSign) ? $workWxSign : [];
            $tmpStr .= 'key=' . $this->client->mchConfig($mchConfig, 'mchKey');
        }

        return strtoupper(md5($tmpStr));
    }
}
