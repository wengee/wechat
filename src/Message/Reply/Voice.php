<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-15 14:25:02 +0800
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
