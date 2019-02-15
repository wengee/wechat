<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-15 14:24:40 +0800
 */
namespace fwkit\Wechat\Message\Reply;

class Image extends ReplyBase
{
    public $mediaId;

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
