<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Utils;

/**
 * XMLParse class
 *
 * 提供提取消息格式中的密文及生成回复消息格式的接口.
 */
class XMLParse
{
    /**
     * 提取出xml数据包中的加密消息
     * @param string $xmltext 待提取的xml字符串
     * @return string 提取出的加密消息字符串
     */
    public static function extract(string $xmltext)
    {
        try {
            $xml = new \DOMDocument();
            $xml->loadXML($xmltext);
            $e = $xml->getElementsByTagName('Encrypt');
            $a = $xml->getElementsByTagName('ToUserName');
            $encrypt = $e->item(0)->nodeValue;
            $tousername = $a->item(0) ? $a->item(0)->nodeValue : null;
            return [ErrorCode::OK, $encrypt, $tousername];
        } catch (\Exception $e) {
            return [ErrorCode::PARSE_XML_ERROR, null, null];
        }
    }

    /**
     * 生成xml消息
     * @param string $encrypt 加密后的消息密文
     * @param string $signature 安全签名
     * @param string $timestamp 时间戳
     * @param string $nonce 随机字符串
     */
    public static function generate(string $encrypt, string $signature, int $timestamp, string $nonce)
    {
        $format = '<xml>
<Encrypt><![CDATA[%s]]></Encrypt>
<MsgSignature><![CDATA[%s]]></MsgSignature>
<TimeStamp>%d</TimeStamp>
<Nonce><![CDATA[%s]]></Nonce>
</xml>';
        return sprintf($format, $encrypt, $signature, $timestamp, $nonce);
    }
}
