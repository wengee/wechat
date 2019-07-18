<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-07-18 18:18:06 +0800
 */
namespace fwkit\Wechat\Message\Reply;

class Text extends ReplyBase
{
    protected $attributes = [
        'content' => '',
    ];

    protected function template(): string
    {
        return '<xml>
                    <ToUserName><![CDATA[{{openId}}]]></ToUserName>
                    <FromUserName><![CDATA[{{accountId}}]]></FromUserName>
                    <CreateTime>{{createTime}}</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[{{content}}]]></Content>
                </xml>';
    }
}
