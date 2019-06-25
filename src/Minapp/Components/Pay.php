<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-06-25 18:33:05 +0800
 */
namespace fwkit\Wechat\Minapp\Components;

use fwkit\Wechat\ComponentBase;
use fwkit\Wechat\Utils\Helper;

class Pay extends ComponentBase
{
    public function sign(string $prepayId, array $mchConfig = [])
    {
        $data = [
            'appId'     => $this->client->getAppId(),
            'timeStamp' => (string) time(),
            'nonceStr'  => Helper::nonceStr(),
            'package'   => 'prepay_id=' . $prepayId,
            'signType'  => 'MD5',
        ];

        $data['paySign'] = $this->signature($data, $mchConfig);
        return $data;
    }

    public function uniOrder(array $data, array $mchConfig = [])
    {
        $xml = $this->toXml($data, $mchConfig);
        $res = $this->post('https://api.mch.weixin.qq.com/pay/unifiedorder', [
            'body' => $xml,
            'withCert' => false,
        ], false, 'xml');

        return $res ? $this->transformKeys($res, [
            'return_code'   => 'returnCode',
            'return_msg'    => 'returnMsg',
            'appid'         => 'appId',
            'mch_id'        => 'mchId',
            'device_info'   => 'deviceInfo',
            'nonce_str'     => 'nonceStr',
            'result_code'   => 'resultCode',
            'err_code'      => 'errCode',
            'err_code_des'  => 'errCodeDes',
            'trade_type'    => 'tradeType',
            'prepay_id'     => 'prepayId',
            'code_url'      => 'codeUrl',
        ]) : null;
    }

    public function queryOrder(array $data, array $mchConfig = [])
    {
        $xml = $this->toXml($data, $mchConfig);
        $res = $this->post('https://api.mch.weixin.qq.com/pay/orderquery', [
            'body' => $xml,
            'withCert' => false,
        ], false, 'xml');

        return $res ? $this->transformKeys($res, [
            'return_code'           => 'returnCode',
            'return_msg'            => 'returnMsg',
            'appid'                 => 'appId',
            'mch_id'                => 'mchId',
            'device_info'           => 'deviceInfo',
            'nonce_str'             => 'nonceStr',
            'result_code'           => 'resultCode',
            'err_code'              => 'errCode',
            'err_code_des'          => 'errCodeDes',
            'openid'                => 'openId',
            'is_subscribe'          => 'isSubscribe',
            'trade_type'            => 'tradeType',
            'trade_state'           => 'tradeState',
            'bank_type'             => 'bankType',
            'total_fee'             => 'totalFee',
            'settlement_total_fee'  => 'settlementTotalFee',
            'fee_type'              => 'feeType',
            'cash_fee'              => 'cashFee',
            'cash_fee_type'         => 'cashFeeType',
            'coupon_fee'            => 'couponFee',
            'coupon_count'          => 'couponCount',
            'out_trade_no'          => 'outTradeNo',
            'time_end'              => 'timeEnd',
            'trade_state_desc'      => 'tradeStateDesc',
        ]) : null;
    }

    public function closeOrder(string $tradeNo, array $mchConfig = [])
    {
        $xml = $this->toXml(['out_trade_no' => $tradeNo], $mchConfig);
        $res = $this->post('https://api.mch.weixin.qq.com/pay/closeorder', [
            'body' => $xml,
            'withCert' => false,
        ], false, 'xml');

        return $res ? $this->transformKeys($res, [
            'return_code'           => 'returnCode',
            'return_msg'            => 'returnMsg',
            'appid'                 => 'appId',
            'mch_id'                => 'mchId',
            'device_info'           => 'deviceInfo',
            'nonce_str'             => 'nonceStr',
            'result_code'           => 'resultCode',
            'err_code'              => 'errCode',
            'err_code_des'          => 'errCodeDes',
        ]) : null;
    }

    public function refund(array $data, array $mchConfig = [])
    {
        $xml = $this->toXml($data, $mchConfig);
        $res = $this->post('https://api.mch.weixin.qq.com/secapi/pay/refund', [
            'body' => $xml,
            'withCert' => $mchConfig,
        ], false, 'xml');

        return $res ? $this->transformKeys($res, [
            'return_code'           => 'returnCode',
            'return_msg'            => 'returnMsg',
            'appid'                 => 'appId',
            'mch_id'                => 'mchId',
            'device_info'           => 'deviceInfo',
            'nonce_str'             => 'nonceStr',
            'result_code'           => 'resultCode',
            'err_code'              => 'errCode',
            'err_code_des'          => 'errCodeDes',
            'transaction_id'        => 'transactionId',
            'out_trade_no'          => 'outTradeNo',
            'out_refund_no'         => 'outRefundNo',
            'refund_id'             => 'refundId',
            'refund_fee'            => 'refundFee',
            'settlement_refund_fee' => 'settlementRefundFee',
            'total_fee'             => 'totalFee',
            'settlement_total_fee'  => 'settlementTotalFee',
            'fee_type'              => 'feeType',
            'cash_fee'              => 'cashFee',
            'cash_fee_type'         => 'cashFeeType',
            'cash_refund_fee'       => 'cashRefundFee',
            'coupon_refund_fee'     => 'couponRefundFee',
            'coupon_refund_count'   => 'couponRefundCount',
        ]) : null;
    }

    public function queryRefund(array $data, array $mchConfig = [])
    {
        $xml = $this->toXml($data, $mchConfig);
        $res = $this->post('https://api.mch.weixin.qq.com/pay/refundquery', [
            'body' => $xml,
            'withCert' => $mchConfig,
        ], false, 'xml');

        return $res ? $this->transformKeys($res, [
            'return_code'           => 'returnCode',
            'return_msg'            => 'returnMsg',
            'appid'                 => 'appId',
            'mch_id'                => 'mchId',
            'nonce_str'             => 'nonceStr',
            'result_code'           => 'resultCode',
            'err_code'              => 'errCode',
            'err_code_des'          => 'errCodeDes',
            'total_refund_count'    => 'totalRefundCount',
            'transaction_id'        => 'transactionId',
            'out_trade_no'          => 'outTradeNo',
            'total_fee'             => 'totalFee',
            'settlement_total_fee'  => 'settlementTotalFee',
            'fee_type'              => 'feeType',
            'cash_fee'              => 'cashFee',
            'refund_count'          => 'refundCount',
        ]) : null;
    }

    protected function toXml(array $data, array $mchConfig = [])
    {
        $data = $this->transformKeys($data, [
            'deviceInfo'    => 'device_info',
            'nonceStr'      => 'nonce_str',
            'outTradeNo'    => 'out_trade_no',
            'feeType'       => 'fee_type',
            'totalFee'      => 'total_fee',
            'clientIp'      => 'spbill_create_ip',
            'timeStart'     => 'time_start',
            'timeExpire'    => 'time_expire',
            'goodsTag'      => 'goods_tag',
            'notifyUrl'     => 'notify_url',
            'tradeType'     => 'trade_type',
            'productId'     => 'product_id',
            'limitPay'      => 'limit_pay',
            'openId'        => 'openid',
            'sceneInfo'     => 'scene_info',
            'transactionId' => 'transaction_id',
            'outRefundNo'   => 'out_refund_no',
            'refundFee'     => 'refund_fee',
            'refundFeeType' => 'refund_fee_type',
            'refundDesc'    => 'refund_desc',
            'refundAccount' => 'refund_account',
            'refundId'      => 'refund_id',
        ]);

        $data += [
            'appid'     => $this->client->getAppId(),
            'mch_id'    => $this->client->mchConfig($mchConfig, 'mchId'),
            'nonce_str' => Helper::createNonceStr(),
        ];

        $data['sign_type'] = 'MD5';
        $data['sign'] = $this->signature($data, $mchConfig);
        $ret = '<xml>';
        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            }

            $ret .= "<{$key}><![CDATA[{$value}]]></{$key}>";
        }
        $ret .= '</xml>';

        return $ret;
    }

    protected function signature(array $data, array $mchConfig = [])
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
