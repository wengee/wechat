<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-06-25 16:25:18 +0800
 */
namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;
use fwkit\Wechat\Utils\Helper;

class Redpack extends ComponentBase
{
    public function send(string $openId, int $amount = 100, array $data = [], array $mchConfig = [], bool $group = false)
    {
        $data['re_openid'] = $openId;
        $data['total_amount'] = $amount;
        $xml = $this->toXml($data, $mchConfig, $group);

        $url = $group ?
            'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack' :
            'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';

        $res = $this->post($url, [
            'body' => $xml,
            'withCert' => $mchConfig,
        ], false, 'xml');

        return $res ? $this->transformKeys($res, [
            'return_code' => 'returnCode',
            'return_msg' => 'returnMsg',
            'result_code' => 'resultCode',
            'err_code' => 'errCode',
            'err_code_des' => 'errCodeDes',
            'mch_billno' => 'mchBillNo',
            'mch_id' => 'mchId',
            'wxappid' => 'appId',
            're_openid' => 'openId',
            'total_amount' => 'amount',
            'send_listid' => 'sendListId',
        ]) : null;
    }

    public function sendGroup(string $openId, int $amount = 100, array $data = [], array $mchConfig = [])
    {
        return $this->send($openId, $amount, $data, $mchConfig, true);
    }

    public function query(string $mchBillNo, string $billType = 'MCHT', array $mchConfig = [])
    {
        $data = [
            'nonce_str' => Helper::createNonceStr(),
            'mch_billno' => $mchBillNo,
            'mch_id' => $this->client->mchConfig($mchConfig, 'mchId'),
            'appid' => $this->client->getAppId(),
            'bill_type' => $billType,
        ];

        $data['sign'] = $this->signature($data, $mchConfig);
        $xml = "<xml>
                    <sign><![CDATA[{$data['sign']}]]></sign>
                    <mch_billno><![CDATA[{$data['mch_billno']}]]></mch_billno>
                    <mch_id><![CDATA[{$data['mch_id']}]]></mch_id>
                    <appid><![CDATA[{$data['appid']}]]></appid>
                    <bill_type><![CDATA[{$data['bill_type']}]]></bill_type>
                    <nonce_str><![CDATA[{$data['nonce_str']}]]></nonce_str>
                </xml>";

        $res = $this->post('https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo', [
            'body' => $xml,
            'withCert' => $mchConfig,
        ], false, 'xml');

        return $res ? $this->transformKeys($res, [
            'return_code' => 'returnCode',
            'return_msg' => 'returnMsg',
            'result_code' => 'resultCode',
            'err_code' => 'errCode',
            'err_code_des' => 'errCodeDes',
            'mch_billno' => 'mchBillNo',
            'mch_id' => 'mchId',
            'detail_id' => 'detailId',
            'send_type' => 'sendType',
            'hb_type' => 'hbType',
            'total_num' => 'num',
            'total_amount' => 'amount',
            'send_time' => 'sendTime',
            'refund_time' => 'refundTime',
            'refund_amount' => 'refundAmount',
            'act_name' => 'actName',
            'hblist' => 'hbList',
            'openid' => 'openId',
            'rcv_time' => 'rcvTime',
        ]) : null;
    }

    private function toXml(array $data, array $mchConfig = [], bool $group = false)
    {
        $data = $this->transformKeys($data, [
            'actName' => 'act_name',
            'clientIp' => 'client_ip',
            'billNo' => 'mch_billno',
            'mchBillNo' => 'mch_billno',
            'openId' => 're_openid',
            'sendName' => 'send_name',
            'amount' => 'total_amount',
            'num' => 'total_num',
            'totalNum' => 'total_num',
            'clientIp' => 'client_ip',
            'sceneId' => 'scene_id',
            'riskInfo' => 'risk_info',
            'amtType' => 'amt_type',
        ]);

        $data += [
            'nonce_str' => Helper::createNonceStr(),
            'mch_billno' => '',
            'mch_id' => $this->client->mchConfig($mchConfig, 'mchId'),
            'wxappid' => $this->client->getAppId(),
            'send_name' => '',
            're_openid' => '',
            'total_amount' => '',
            'total_num' => 1,
            'wishing' => '',//祝福语
            'act_name' => '',
            'remark' => '',
            'client_ip' => '',
            'scene_id' => '',
            'risk_info' => '',
        ];

        if (empty($data['mch_billno'])) {
            $microTime = microtime(true) * 10000;
            $mchBillNo = 'RP' . $this->client->getAppId() . $microTime;
            $data['mch_billno'] = substr($mchBillNo, 0, 28);
        }

        $amtType = '';
        if ($group) {
            $data['amt_type'] = $data['amt_type'] ?? 'ALL_RAND';
            $amtType = "<amt_type><![CDATA[{$data['amt_type']}]]></amt_type>";
        }

        $data['sign'] = $this->signature($data, $mchConfig);
        return "<xml>
                    <sign><![CDATA[{$data['sign']}]]></sign>
                    <mch_billno><![CDATA[{$data['mch_billno']}]]></mch_billno>
                    <mch_id><![CDATA[{$data['mch_id']}]]></mch_id>
                    <wxappid><![CDATA[{$data['wxappid']}]]></wxappid>
                    <send_name><![CDATA[{$data['send_name']}]]></send_name>
                    <re_openid><![CDATA[{$data['re_openid']}]]></re_openid>
                    <total_amount><![CDATA[{$data['total_amount']}]]></total_amount>{$amtType}
                    <total_num><![CDATA[{$data['total_num']}]]></total_num>
                    <wishing><![CDATA[{$data['wishing']}]]></wishing>
                    <client_ip><![CDATA[{$data['client_ip']}]]></client_ip>
                    <act_name><![CDATA[{$data['act_name']}]]></act_name>
                    <remark><![CDATA[{$data['remark']}]]></remark>
                    <scene_id><![CDATA[{$data['scene_id']}]]></scene_id>
                    <nonce_str><![CDATA[{$data['nonce_str']}]]></nonce_str>
                    <risk_info><![CDATA[{$data['risk_info']}]]></risk_info>
                </xml>";
    }

    private function signature(array $data, array $mchConfig = [])
    {
        $data = array_filter($data, 'strlen');
        ksort($data);
        $tmpStr = '';
        foreach ($data as $key => $value) {
            $tmpStr .= $key . '=' . $value . '&';
        }

        $tmpStr .= 'key=' . $this->client->mchConfig($mchConfig, 'mchKey');
        return strtoupper(md5($tmpStr));
    }
}
