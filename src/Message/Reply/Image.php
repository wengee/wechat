<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-07-18 18:20:22 +0800
 */
namespace fwkit\Wechat\Message\Reply;

class Image extends ReplyBase
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
                    <MsgType><![CDATA[image]]></MsgType>
                    <Image>
                        <MediaId><![CDATA[{{mediaId}}]]></MediaId>
                    </Image>
                </xml>';
    }
}
