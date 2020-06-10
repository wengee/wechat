<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Message\Reply;

class Voice extends ReplyBase
{
    protected $attributes = [
        'mediaId' => '',
    ];

    protected function template(): string
    {
        return '<xml>
                    <ToUserName><![CDATA[{{openId}}]]></ToUserName>
                    <FromUserName><![CDATA[{{accountId}}]]></FromUserName>
                    <CreateTime>{{createTime}}</CreateTime>
                    <MsgType><![CDATA[voice]]></MsgType>
                    <Voice>
                        <MediaId><![CDATA[{{mediaId}}]]></MediaId>
                    </Voice>
                </xml>';
    }
}
